<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{

    /**
     * @var UserServiceInterface $userService
     */
    private $userService;

    /**
     * SecurityController constructor.
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @Route("/login", name="security_login")
     * @return Response
     */
    public function login()
    {
        return $this->render('user/security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception('Logout failed!');
    }

    /**
     * @Route("/register", name="security_register", methods={"GET"})
     * @return Response
     */
    public function registerGet()
    {
        return $this->render('user/security/register.html.twig', ['form' => $this->createForm(UserType::class)->createView()]);
    }

    /**
     * @Route("/register", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function registerPost(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $this->userService->save($user);

        return $this->render('user/security/login.html.twig');
    }

}

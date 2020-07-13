<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Service\Article\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /** @var ProductServiceInterface $articleService */
    private $articleService;

    /**
     * DefaultController constructor.
     * @param ProductServiceInterface $articleService
     */
    public function __construct(ProductServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function homepage()
    {
        return $this->render('home/index.html.twig', [ 'products' => $this->articleService->getAllPublicProducts()]);
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUs() {
        return $this->render('home/about_us.html.twig');
    }

    /**
     * @Route("/contact-us", name="contact_us")
     */
    public function contactUs() {
        return $this->render('home/contact_us.html.twig');
    }

    /**
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function blogIndex() {
        return $this->render('blog/blog_index.html.twig');
    }

}

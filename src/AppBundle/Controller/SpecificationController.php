<?php

namespace AppBundle\Controller;

use AppBundle\Service\Specification\SpecificationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecificationController extends Controller
{
    /** @var SpecificationServiceInterface $specificationService */
    private $specificationService;

    /**
     * SpecificationController constructor.
     * @param SpecificationServiceInterface $specificationService
     */
    public function __construct(SpecificationServiceInterface $specificationService)
    {
        $this->specificationService = $specificationService;
    }

    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function test()
    {
        return $this->render(
            'admin_panel/shop/products/specifications/view_all_specifications.html.twig',
            [
                'specifications' => $this->specificationService->getAll(),
            ]
        );
    }
}

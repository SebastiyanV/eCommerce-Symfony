<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Specification;
use AppBundle\Form\SpecificationType;
use AppBundle\Service\Product\ProductServiceInterface;
use AppBundle\Service\Specification\SpecificationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecificationController extends Controller
{
    /** @var SpecificationServiceInterface $specificationService */
    private $specificationService;

    /** @var ProductServiceInterface $productService */
    private $productService;

    /**
     * SpecificationController constructor.
     * @param SpecificationServiceInterface $specificationService
     * @param ProductServiceInterface $productService
     */
    public function __construct(
        SpecificationServiceInterface $specificationService,
        ProductServiceInterface $productService
    ) {
        $this->specificationService = $specificationService;
        $this->productService = $productService;
    }

    /**
     * @Route("/admin/products/{id}/specifications/create", name="admin_products_specifications_create", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function adminSpecificationCreate(int $id)
    {
        $product = $this->productService->getOneById($id);

        return $this->render(
            'admin_panel/shop/products/specifications/create_specification.html.twig',
            [
                'product' => $product,
                'form' => $this->createForm(SpecificationType::class)->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/products/{id}/specifications/create", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminSpecificationCreateProcess(Request $request, int $id)
    {
        $specification = new Specification();
        $product = $this->productService->getOneById($id);

        $specification->setProducts($product);

        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        $this->specificationService->save($specification);
        $this->addFlash('success', 'Successfully added a specification to this product!');

        return $this->redirectToRoute('admin_products_specifications_create', ['id' => $product->getId()]);
    }

    /**
     * @Route("/admin/products/{productId}/specification/edit/{specificationId}", name="admin_products_specification_edit", methods={"GET"})
     * @param int $productId
     * @param int $specificationId
     * @return Response
     */
    public function adminSpecificationEdit(int $productId, int $specificationId)
    {
        return $this->render(
            'admin_panel/shop/products/specifications/edit_specification.html.twig',
            [
                'product' => $this->productService->getOneById($productId),
                'specification' => $this->specificationService->getOneById($specificationId),
                'form' => $this->createForm(SpecificationType::class)->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/products/{productId}/specification/edit/{specificationId}", methods={"POST"})
     * @param int $productId
     * @param int $specificationId
     * @param Request $request
     */
    public function adminSpecificationEditProcess(int $productId, int $specificationId, Request $request)
    {
        $specification = $this->specificationService->getOneById($specificationId);
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        $this->specificationService->edit($specification);
        $this->addFlash('success', 'Specification updated successfully!');

        return $this->redirectToRoute('admin_products_specifications_create', ['id' => $productId]);
    }
}

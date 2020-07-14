<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionType;
use AppBundle\Service\Article\ProductServiceInterface;
use AppBundle\Service\Orders\OrdersService;
use AppBundle\Service\Promotion\PromotionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends Controller
{
    /**
     * @var ProductServiceInterface $productService
     */
    private $productService;

    /**
     * @var PromotionServiceInterface $promotionService
     */
    private $promotionService;

    /**
     * @var OrdersService $ordersService
     */
    private $ordersService;

    /**
     * PromotionController constructor.
     * @param ProductServiceInterface $productService
     * @param PromotionServiceInterface $promotionService
     * @param OrdersService $ordersService
     */
    public function __construct(
        ProductServiceInterface $productService,
        PromotionServiceInterface $promotionService,
        OrdersService $ordersService
    ) {
        $this->productService = $productService;
        $this->promotionService = $promotionService;
        $this->ordersService = $ordersService;
    }

    /**
     * @Route("/admin/products/fast_promotion/{id}", name="admin_product_fast_promotion")
     * @param int $id
     * @return RedirectResponse
     */
    public function fastPromotion(int $id)
    {
        $product = $this->productService->getOneById($id);
        $this->promotionService->turnProductOnFastPromotion($product);

        return $this->redirectToRoute('admin_products_view_by_id', ['id' => $product->getId()]);
    }

    /**
     * @Route("/admin/products/promotion/delete/{id}", name="admin_product_promotion_delete")
     * @param int $id
     * @return RedirectResponse
     */
    public function stopPromotion(int $id)
    {
        $product = $this->productService->getOneById($id);

        /** @var Orders $order */
        foreach ($this->ordersService->getAll() as $order) {
            if ($order->getProduct() === $product) {
                $this->addFlash(
                    'danger',
                    "User with ID: ".$order->getCart()->getUser()->getId().
                    " has this product in his cart! You can't remove the promotion!"
                );

                return $this->redirectToRoute('admin_products_view_by_category_id');
            }
        }


        $this->promotionService->delete($product);

        return $this->redirectToRoute('admin_products_view_by_id', ['id' => $product->getId()]);
    }

    /**
     * @Route("/admin/products/set-promo/{id}", name="admin_products_set_promotion", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function createPromotion(int $id)
    {
        $product = $this->productService->getOneById($id);

        return $this->render(
            'admin_panel/shop/products/promotions/create_promotion.html.twig',
            [
                'form' => $this->createForm(PromotionType::class)->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @Route("/admin/products/set-promo/{id}", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function createPromotionProcess(Request $request, int $id)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        $product = $this->productService->getOneById($id);
        $promotion->setProduct($product);

        if ($promotion->getPercentage() >= 0 && $promotion->getPercentage() <= 100) {
            $this->promotionService->insert($promotion);

            $this->addFlash('success', 'You successfully set promotion ot this article!');

            return $this->redirectToRoute('admin_products_set_promotion', ['id' => $product->getId()]);
        }

        $this->addFlash('danger', 'Invalid promotion!');

        return $this->redirectToRoute('admin_products_view_by_id', ['id' => $product->getId()]);

    }

    /**
     * @Route("/admin/products/edit-promo/{id}", name="admin_products_edit_promotion", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function editPromotion(int $id)
    {
        $product = $this->productService->getOneById($id);

        return $this->render(
            'admin_panel/shop/products/promotions/edit_promotion.html.twig',
            [
                'product' => $product,
                'promotion' => $product->getPromotion(),
                'form' => $this->createForm(PromotionType::class)->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/products/edit-promo/{id}", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function editPromotionProcess(Request $request, int $id)
    {
        $product = $this->productService->getOneById($id);
        $promotion = $product->getPromotion();

        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($promotion->getPercentage() >= 0 && $promotion->getPercentage() <= 100) {
            $this->promotionService->update($promotion);

            $this->addFlash('success', 'You successfully set promotion ot this article!');

            return $this->redirectToRoute('admin_products_edit_promotion', ['id' => $product->getId()]);
        }

        $this->addFlash('danger', 'Invalid promotion!');

        return $this->redirectToRoute('admin_products_view_by_id', ['id' => $product->getId()]);
    }
}

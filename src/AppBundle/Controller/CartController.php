<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use AppBundle\Service\Product\ProductServiceInterface;
use AppBundle\Service\Cart\CartServiceInterface;
use AppBundle\Service\Orders\OrdersService;
use AppBundle\Service\Orders\OrdersServiceInterface;
use AppBundle\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{
    /** @var ProductServiceInterface $productService */
    private $productService;

    /** @var UserServiceInterface $userService */
    private $userService;

    /** @var CartServiceInterface $cartService */
    private $cartService;

    /** @var OrdersServiceInterface $ordersService */
    private $ordersService;

    /**
     * CartController constructor.
     * @param ProductServiceInterface $productService
     * @param UserServiceInterface $userService
     * @param CartServiceInterface $cartService
     * @param OrdersServiceInterface $ordersService
     */
    public function __construct(
        ProductServiceInterface $productService,
        UserServiceInterface $userService,
        CartServiceInterface $cartService,
        OrdersServiceInterface $ordersService
    ) {
        $this->productService = $productService;
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->ordersService = $ordersService;
    }

    /**
     * @Route("/cart", name="cart_view")
     * @return Response
     */
    public function cartView()
    {
        if ($this->userService->getCurrentUser()->getCart()) {

            $orders = $this
                ->cartService
                ->getCartByUser($this->userService->getCurrentUser())
                ->getOrders();


            $products = [];
            /** @var Orders $order */
            foreach ($orders as $order) {
                $products[] = $order->getProduct();
            }


            return $this->render(
                'user/cart/view_cart.html.twig',
                [
                    'currentUser' => $this->userService->getCurrentUser(),
                    'orders' => $orders,
                ]
            );
        }

        return $this->render(
            'user/cart/view_cart.html.twig',
            [
                'currentUser' => $this->userService->getCurrentUser(),
                'products' => [],
            ]
        );
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @param int $id
     * @return RedirectResponse
     */
    public function addProductToCart(int $id)
    {

        $currentUser = $this->userService->getCurrentUser();

        if (null === $currentUser) {
            $this->redirectToRoute('view_all_products');
        }

        $product = $this->productService->getOneById($id);

        $orders = $this
            ->cartService
            ->getCartByUser($this->userService->getCurrentUser())
            ->getOrders();

        $existingProduct = false;

        /** @var Orders $order */
        foreach ($orders as $order) {
            if ($order->getProduct()->getId() == $product->getId()) {
                $order->setQuantity($order->getQuantity() + 1);
                $this->ordersService->updateOrder($order);
                $existingProduct = true;
            }
        }

        if (!$existingProduct) {
            $order = new Orders();
            $order->setCart($currentUser->getCart());
            $order->setProduct($product);
            $order->setQuantity(1);

            $this->ordersService->addOrder($order);
        }

        return $this->redirectToRoute('view_all_products');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_remove_order")
     * @param Orders $order
     * @return RedirectResponse
     */
    public function removeOrderFromCart(Orders $order)
    {
        $this->ordersService->deleteOrder($order);

        return $this->redirectToRoute("cart_view");
    }

    /**
     * @Route("/cart/order/addQuantity/{id}", name="cart_order_add_one_quantity")
     * @param Orders $order
     * @return RedirectResponse
     */
    public function addOneQuantity(Orders $order) {
        $order->setQuantity($order->getQuantity() + 1);
        $this->ordersService->updateOrder($order);
        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart/order/removeQuantity/{id}", name="cart_order_remove_one_quantity")
     * @param Orders $order
     * @return RedirectResponse
     */
    public function removeOneQuantity(Orders $order) {

        if($order->getQuantity() <= 1) {
            return $this->redirectToRoute('cart_view');
        }

        $order->setQuantity($order->getQuantity() - 1);
        $this->ordersService->updateOrder($order);
        return $this->redirectToRoute('cart_view');
    }
}

<?php


namespace AppBundle\Service\Cart;


use AppBundle\Entity\Cart;
use AppBundle\Entity\User;
use AppBundle\Repository\CartRepository;

class CartService implements CartServiceInterface
{

    /** @var CartRepository $cartRepository */
    private $cartRepository;

    /**
     * CartService constructor.
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param User $user
     * @return Cart|null
     */
    public function getCartByUser(User $user): ?Cart
    {
        return $this->cartRepository->findCartByUser($user);
    }

    /**
     * @param Cart $cart
     * @return bool
     */
    public function createCart(Cart $cart): bool
    {
        return $this->cartRepository->insertCart($cart);
    }
}
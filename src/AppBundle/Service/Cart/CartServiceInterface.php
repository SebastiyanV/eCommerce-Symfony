<?php


namespace AppBundle\Service\Cart;


use AppBundle\Entity\Cart;
use AppBundle\Entity\User;

interface CartServiceInterface
{
    public function getCartByUser(User $user): ?Cart;
    public function createCart(Cart $cart): bool;
}
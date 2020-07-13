<?php


namespace AppBundle\Service\Orders;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;

interface OrdersServiceInterface
{
    public function addOrder(Orders $order): bool;

    public function deleteOrder(Orders $orders): bool;

    public function getAll(): ?array;
}
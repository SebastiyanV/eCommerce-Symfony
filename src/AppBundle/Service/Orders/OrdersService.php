<?php


namespace AppBundle\Service\Orders;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use AppBundle\Repository\OrdersRepository;

class OrdersService implements OrdersServiceInterface
{

    /**
     * @var OrdersRepository $ordersRepository
     */
    private $ordersRepository;

    /**
     * OrdersService constructor.
     * @param OrdersRepository $ordersRepository
     */
    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    /**
     * @param Orders $order
     * @return bool
     */
    public function addOrder(Orders $order): bool
    {
        return $this->ordersRepository->insertOrder($order);
    }

    /**
     * @param Orders $orders
     * @return bool
     */
    public function deleteOrder(Orders $orders): bool
    {
        return $this->ordersRepository->deleteOrder($orders);
    }

    /**
     * @param Orders $orders
     * @return bool
     */
    public function updateOrder(Orders $orders): bool {
        return $this->ordersRepository->updateOrder($orders);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->ordersRepository->findAll();
    }
}
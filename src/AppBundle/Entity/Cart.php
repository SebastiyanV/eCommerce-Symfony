<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="carts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="cart")
     */
    private $user;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="cart")
     */
    private $orders;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders(ArrayCollection $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * @return int
     */
    public function totalCost()
    {
        $totalCost = 0;

        /** @var Orders $order */
        foreach ($this->getOrders() as $order) {
                if($order->getProduct()->getPromotion()) {
                    $totalCost += $order->getProduct()->getPromoPrice() * $order->getQuantity();
                } else {
                    $totalCost += $order->getProduct()->getPrice() * $order->getQuantity();
                }
        }

        return $totalCost;
    }

    /**
     * @return int
     */
    public function productsCount()
    {
        $count = 0;

        /** @var Orders $order */
        foreach ($this->orders as $order) {
            $count += $order->getQuantity();
        }

        return $count;
    }
}


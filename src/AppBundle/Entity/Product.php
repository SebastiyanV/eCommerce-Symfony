<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", nullable=false)
     *
     *
     */
    private $image;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var bool
     * @ORM\Column(name="is_public", options={"default": 1})
     */
    private $isPublic;

    /**
     * @var Orders
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Orders", mappedBy="product")
     *
     */
    private $order;

    /**
     * @var Promotion
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Promotion", mappedBy="product")
     */
    private $promotion;

    /**
     * Product constructor.
     * @param bool $isPublic
     */
    public function __construct()
    {
        $this->isPublic = 1;
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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPromoPrice()
    {
        return $this->price - ($this->price * ($this->promotion->getPercentage() / 100));
    }

    /**
     * @return float|int
     */
    public function getFinalPrice() {
        if($this->getPromotion()) {
            return $this->price - ($this->price * ($this->promotion->getPercentage() / 100));
        } else {
            return $this->getPrice();
        }
    }

    /**
     * @return float|int
     */
    public function getQuantityPrice() {
        /** @var Orders $order */
        foreach ($this->order as $order) {
            if($order->getProduct()->getId() == $this->getId()) {
                return $this->getPrice() * $order->getQuantity();
            }
        }
    }

    /**
     * @return float|int
     */
    public function getQuantityPromoPrice() {
        /** @var Orders $order */
        foreach ($this->order as $order) {
            if($order->getProduct()->getId() == $this->getId()) {
                return $this->getPromoPrice() * $order->getQuantity();
            }
        }
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)  //i string v skobite
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        if ($this->summary === null) {
            $this->setSummary();
        }

        return $this->summary;
    }

    /**
     * @param string
     */
    public function setSummary()
    {
        $this->summary = substr($this->getDescription(), 0, 100)."...";
    }

    /**
     * @return bool
     */
    public function isIsPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     */
    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return Orders
     */
    public function getOrder(): ?Orders
    {
        /** @var Orders $item */
        foreach ($this->order as $item) {
            if ($item->getProduct()->getId() == $this->id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param Orders $order
     */
    public function setOrder(Orders $order): void
    {
        $this->order = $order;
    }

    /**
     * @return Promotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param Promotion $promotion
     */
    public function setPromotion(Promotion $promotion): void
    {
        $this->promotion = $promotion;
    }


}


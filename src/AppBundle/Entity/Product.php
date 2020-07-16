<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var Specification
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Specification", mappedBy="products")
     */
    private $specifications;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductImage", mappedBy="product")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductViews", mappedBy="product")
     * @var ArrayCollection
     */
    private $views;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->isPublic = 1;
        $this->images = new ArrayCollection();
        $this->views = new ArrayCollection();
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
    public function getFinalPrice()
    {
        if ($this->getPromotion()) {
            return $this->price - ($this->price * ($this->promotion->getPercentage() / 100));
        } else {
            return $this->getPrice();
        }
    }

    /**
     * @return float|int
     */
    public function getQuantityPrice()
    {
        /** @var Orders $order */
        foreach ($this->order as $order) {
            if ($order->getProduct()->getId() == $this->getId()) {
                return $this->getPrice() * $order->getQuantity();
            }
        }

        return 0;
    }

    /**
     * @return float|int
     */
    public function getQuantityPromoPrice()
    {
        /** @var Orders $order */
        foreach ($this->order as $order) {
            if ($order->getProduct()->getId() == $this->getId()) {
                return $this->getPromoPrice() * $order->getQuantity();
            }
        }

        return 0;
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

    /**
     * @return array|null|object
     */
    public function getSpecifications(): ?array
    {
        $specifications = [];
        foreach ($this->specifications as $specification) {
            $specifications[] = $specification;
        }

        return $specifications;
    }

    /**
     * @param Specification $specifications
     */
    public function setSpecifications(Specification $specifications): void
    {
        $this->specifications = $specifications;
    }

    /**
     * @return mixed
     */
    public function getFirstImage()
    {
        $images = $this->getImages();
        $data = [];
        /** @var ProductImage $image */
        foreach ($images as $image) {
            $data[] = $image->getImageFileName();
        }

        return array_shift($data);
    }

    /**
     * @return ProductImage|null
     */
    public function getTopImage()
    {
        $images = $this->getImages();

        /** @var ProductImage $image */
        foreach ($images as $image) {
            if ($image->isTopImage()) {
                return $image;
            }
        }

        return null;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $images
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * @return ArrayCollection
     */
    public function getViews(): ArrayCollection
    {
        return $this->views;
    }

    /**
     * @param ArrayCollection $views
     */
    public function setViews(ArrayCollection $views): void
    {
        $this->views = $views;
    }

}


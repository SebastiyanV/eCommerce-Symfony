<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductImage
 *
 * @ORM\Table(name="products_images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductImageRepository")
 */
class ProductImage
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
     * @ORM\Column(name="image_file_name", type="string", length=255)
     */
    private $imageFileName;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="images")
     */
    private $product;

    /**
     * @var bool
     * @ORM\Column(name="top_image", type="boolean")
     */
    private $topImage;

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
     * Set name
     *
     * @param string $name
     *
     * @return ProductImage
     */
    public function setName($name)
    {
        $this->imageFileName = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->imageFileName;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getImageFileName()
    {
        return $this->imageFileName;
    }

    /**
     * @param string $imageFileName
     */
    public function setImageFileName(string $imageFileName)
    {
        $this->imageFileName = $imageFileName;
    }

    /**
     * @return bool
     */
    public function isTopImage(): bool
    {
        return $this->topImage;
    }

    /**
     * @param bool $topImage
     */
    public function setTopImage(bool $topImage): void
    {
        $this->topImage = $topImage;
    }

}


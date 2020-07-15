<?php


namespace AppBundle\Service\ProductImage;


use AppBundle\Entity\ProductImage;
use AppBundle\Repository\ProductImageRepository;

class ProductImageService implements ProductImageServiceInterface
{

    /** @var ProductImageRepository $productImageRepository */
    private $productImageRepository;

    /**
     * ProductImageService constructor.
     * @param ProductImageRepository $productImageRepository
     */
    public function __construct(ProductImageRepository $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;
    }

    public function add(ProductImage $image): bool
    {
        return $this->productImageRepository->insert($image);
    }

}
<?php


namespace AppBundle\Service\ProductImage;


use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use AppBundle\Repository\ProductImageRepository;
use AppBundle\Service\Product\ProductServiceInterface;

class ProductImageService implements ProductImageServiceInterface
{

    /** @var ProductImageRepository $productImageRepository */
    private $productImageRepository;

    /** @var ProductServiceInterface $productService */
    private $productService;

    /**
     * ProductImageService constructor.
     * @param ProductImageRepository $productImageRepository
     * @param ProductServiceInterface $productService
     */
    public function __construct(ProductImageRepository $productImageRepository, ProductServiceInterface $productService)
    {
        $this->productImageRepository = $productImageRepository;
        $this->productService = $productService;
    }

    public function add(ProductImage $productImage): bool
    {
        $product = $productImage->getProduct();

        $hasTopImage = false;

        /** @var ProductImage $image */
        foreach ($product->getImages() as $image) {
            if ($image->isTopImage()) {
                $hasTopImage = true;
            }
        }

        if (!$hasTopImage) {
            $productImage->setTopImage(true);
        } else {
            $productImage->setTopImage(false);
        }

        return $this->productImageRepository->insert($productImage);
    }

}
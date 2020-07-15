<?php


namespace AppBundle\Service\ProductImage;


use AppBundle\Entity\ProductImage;

interface ProductImageServiceInterface
{
    public function add(ProductImage $image): bool;
}
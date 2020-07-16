<?php


namespace AppBundle\Service\ProductImage;


use AppBundle\Entity\ProductImage;

interface ProductImageServiceInterface
{
    public function add(ProductImage $image): bool;

    public function edit(ProductImage $image): bool;

    public function delete(ProductImage $image): bool;

    public function getOneById(int $id): ?ProductImage;
}
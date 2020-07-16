<?php


namespace AppBundle\Service\Product;


use AppBundle\Entity\Product;

interface ProductServiceInterface
{
    public function getOneById(int $id): ?Product;

    public function save(Product $article): bool;

    public function edit(Product $article): bool;

    public function remove(int $id): bool;

    public function getAll(): ?array;

    public function getAllByCategoryId(int $id): ?array;

    public function getAllPublicProducts(): ?array;

    public function productCount(): int;

    public function changePublic(Product $product): bool;
}
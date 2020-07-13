<?php


namespace AppBundle\Service\Specification;


use AppBundle\Entity\Product;
use AppBundle\Entity\Specification;

interface SpecificationServiceInterface
{
    public function save(Specification $specification): bool;

    public function edit(Specification $specification): bool;

    public function delete(Specification $specification): bool;

    public function getAll(): ?array;

    public function getAllByProduct(Product $product): ?array;

    public function getOneById(int $id): ?Specification;

}
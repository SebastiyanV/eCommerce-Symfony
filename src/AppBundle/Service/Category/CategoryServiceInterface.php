<?php


namespace AppBundle\Service\Category;


use AppBundle\Entity\Category;

interface CategoryServiceInterface
{
    public function save(Category $category): bool;
    public function edit(Category $category): bool;
    public function remove(int $id): bool;
    public function getAll(): ?array;
    public function getOneById(int $id): ?Category;
}
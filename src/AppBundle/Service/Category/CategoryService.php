<?php


namespace AppBundle\Service\Category;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Service\Product\ProductServiceInterface;
use AppBundle\Service\User\UserServiceInterface;

class CategoryService implements CategoryServiceInterface
{

    /**
     * @var CategoryRepository $categoryRepository
     */
    private $categoryRepository;

    /**
     * @var UserServiceInterface $userService
     */
    private $userService;

    /** @var ProductServiceInterface $productService */
    private $productService;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     * @param UserServiceInterface $userService
     */
    public function __construct(CategoryRepository $categoryRepository, UserServiceInterface $userService, ProductServiceInterface $productService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function getOneById(int $id): ?Category
    {
        return $this->categoryRepository->getOneById($id);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @return array
     */
    public function getAllCategoriesNames()
    {
        $categoriesNames = [];
        $allCategories = $this->getAll();

        /** @var Category $category */
        foreach ($allCategories as $category) {
            $categoriesNames[] = ($category->getName());
        }

        return $categoriesNames;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function save(Category $category): bool
    {
        $category->setAuthor($this->userService->getCurrentUser());

        return $this->categoryRepository->insert($category);
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function edit(Category $category): bool
    {
        return $this->categoryRepository->update($category);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        $category = $this->getOneById($id);

        return $this->categoryRepository->delete($category);
    }

}
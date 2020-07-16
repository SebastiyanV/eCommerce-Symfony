<?php


namespace AppBundle\Service\Product;


use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;

class ProductService implements ProductServiceInterface
{

    /**
     * @var ProductRepository $articleRepository
     */
    private $articleRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $articleRepository
     */
    public function __construct(ProductRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getOneById(int $id): ?Product
    {
        return $this->articleRepository->getOneById($id);
    }

    /**
     * @param Product $article
     * @return bool
     */
    public function save(Product $article): bool
    {
        return $this->articleRepository->insert($article);
    }

    /**
     * @param Product $article
     * @return bool
     */
    public function edit(Product $article): bool
    {
        return $this->articleRepository->update($article);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        $article = $this->getOneById($id);

        return $this->articleRepository->delete($article);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->articleRepository->findAll();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getAllByCategoryId(int $id): ?array
    {
        return $this->articleRepository->findBy(['category' => $id]);
    }

    /**
     * @return array|null
     */
    public function getAllPublicProducts(): ?array
    {
        $products = $this->getAll();
        $publicProducts = [];

        /** @var Product $product */
        foreach ($products as $product) {
            if ($product->isIsPublic() === true) {
                $publicProducts[] = $product;
            }
        }

        return $publicProducts;

    }

    /**
     * @return int
     */
    public function productCount(): int
    {
        $count = 0;
        foreach ($this->getAll() as $item) {
            $count++;
        }

        return $count;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function changePublic(Product $product): bool
    {
        if ($product->isIsPublic()) {
            $product->setIsPublic(false);

            return $this->edit($product);
        } else {
            $product->setIsPublic(true);

            return $this->edit($product);
        }
    }
}
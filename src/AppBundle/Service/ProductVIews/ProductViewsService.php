<?php


namespace AppBundle\Service\ProductVIews;


use AppBundle\Entity\ProductViews;
use AppBundle\Repository\ProductViewsRepository;

class ProductViewsService implements ProductViewsServiceInterface
{

    /**
     * @var ProductViewsRepository $productViewRepository
     *
     */
    private $productViewRepository;

    /**
     * ProductViewsService constructor.
     * @param ProductViewsRepository $productViewRepository
     */
    public function __construct(ProductViewsRepository $productViewRepository)
    {
        $this->productViewRepository = $productViewRepository;
    }

    /**
     * @param ProductViews $view
     * @return bool
     */
    public function add(ProductViews $view): bool
    {
        return $this->productViewRepository->insert($view);
    }

    public function getLastViewByIpAndProduct(string $ipAddress, int $productId)
    {
        return $this->productViewRepository->getLastViewByIpAndProduct($ipAddress, $productId);
    }
}
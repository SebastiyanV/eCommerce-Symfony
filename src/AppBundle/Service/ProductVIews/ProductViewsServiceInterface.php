<?php


namespace AppBundle\Service\ProductVIews;


use AppBundle\Entity\ProductViews;

interface ProductViewsServiceInterface
{
    public function add(ProductViews $view): bool;

    public function getLastViewByIpAndProduct(string $ipAddress, int $productId);
}
<?php


namespace AppBundle\Service\Promotion;


use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;

interface PromotionServiceInterface
{
    public function turnProductOnFastPromotion(Product $product): bool;

    public function insert(Promotion $promotion): bool;

    public function update(Promotion $promotion): bool;

    public function delete(Product $product): bool;
}
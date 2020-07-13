<?php


namespace AppBundle\Service\Promotion;


use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Repository\PromotionRepository;

class PromotionService implements PromotionServiceInterface
{
    /**
     * @var PromotionRepository $promotionRepository
     */
    private $promotionRepository;

    /**
     * PromotionService constructor.
     * @param PromotionRepository $promotionRepository
     */
    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function turnProductOnFastPromotion(Product $product): bool
    {
        $promotion = new Promotion();
        $promotion->setProduct($product);
        $promotion->setPercentage(20);

        return $this->promotionRepository->insert($promotion);
    }

    public function insert(Promotion $promotion): bool
    {
        return $this->promotionRepository->insert($promotion);
    }

    public function update(Promotion $promotion): bool
    {
        return $this->promotionRepository->update($promotion);
    }

    public function delete(Product $product): bool
    {
        $promotion = $product->getPromotion();
        return $this->promotionRepository->delete($promotion);
    }
}
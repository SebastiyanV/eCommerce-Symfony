<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\OptimisticLockException;

/**
 * ProductImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductImageRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ProductImageRepository constructor.
     * @param EntityManagerInterface $em
     * @param Mapping\ClassMetadata|null $class
     */
    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class = null)
    {
        parent::__construct($em, $class == null ? new Mapping\ClassMetadata(ProductImage::class) : $class);
    }

    /**
     * @param ProductImage $image
     * @return bool
     */
    public function insert(ProductImage $image)
    {
        try {
            $this->_em->persist($image);
            $this->_em->flush();

            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

}

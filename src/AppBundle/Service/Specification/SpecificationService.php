<?php


namespace AppBundle\Service\Specification;


use AppBundle\Entity\Product;
use AppBundle\Entity\Specification;
use AppBundle\Repository\SpecificationRepository;

class SpecificationService implements SpecificationServiceInterface
{

    /** @var SpecificationRepository $specificationRepository */
    private $specificationRepository;

    /**
     * SpecificationService constructor.
     * @param SpecificationRepository $specificationRepository
     */
    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    /**
     * @param Specification $specification
     * @return bool
     */
    public function save(Specification $specification): bool
    {
        return $this->specificationRepository->insert($specification);
    }

    /**
     * @param Specification $specification
     * @return bool
     */
    public function edit(Specification $specification): bool
    {
        return $this->specificationRepository->update($specification);
    }

    /**
     * @param Specification $specification
     * @return bool
     */
    public function delete(Specification $specification): bool
    {
        return $this->specificationRepository->delete($specification);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->specificationRepository->findAll();
    }

    /**
     * @param Product $product
     * @return array|null
     */
    public function getAllByProduct(Product $product): ?array
    {
        return $this->specificationRepository->findBy(['products' => $product]);
    }

    /**
     * @param int $id
     * @return Specification|null
     */
    public function getOneById(int $id): ?Specification
    {
        return $this->specificationRepository->findOneBy(['id' => $id]);
    }

}
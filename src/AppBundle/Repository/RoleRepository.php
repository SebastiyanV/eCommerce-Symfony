<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $em
     * @param Mapping\ClassMetadata|null $class
     */
    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class = null)
    {
        parent::__construct($em, $class == null ? new Mapping\ClassMetadata(Role::class) : $class);
    }

}

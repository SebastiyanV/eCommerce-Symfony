<?php


namespace AppBundle\Service\Role;


use AppBundle\Entity\Role;
use AppBundle\Repository\RoleRepository;

class RoleService implements RoleServiceInterface
{

    /**
     * @var RoleRepository $roleRepository
     */
    private $roleRepository;

    /**
     * RoleService constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param string $criteria
     * @return Role
     */
    public function findOneBy(string $criteria): Role
    {
        return $this->roleRepository->findOneBy(['name' => $criteria]);
    }
}
<?php


namespace AppBundle\Service\User;


use AppBundle\Entity\User;

interface UserServiceInterface
{
    public function getCurrentUser(): ?User;
    public function save(User $user): bool;
}
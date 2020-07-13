<?php


namespace AppBundle\Service\User;


use AppBundle\Entity\Cart;
use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Cart\CartServiceInterface;
use Symfony\Component\Security\Core\Security;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository $roleRepository
     */
    private $roleRepository;

    /**
     * @var Security
     */
    private $security;

    /** @var CartServiceInterface $cartService */
    private $cartService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param Security $security
     * @param CartServiceInterface $cartService
     */
    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        Security $security,
        CartServiceInterface $cartService
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->security = $security;
        $this->cartService = $cartService;
    }

    public function save(User $user): bool
    {
        try {

            $password = $user->getPassword();
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($passwordHash);

            $roleUser = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($roleUser);

            $this->userRepository->insert($user);

            $cart = new Cart();
            $cart->setUser($user);
            $this->cartService->createCart($cart);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return User|null|object
     */
    public function getCurrentUser(): ?User
    {
        return $this->security->getUser();
    }
}
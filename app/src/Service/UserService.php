<?php

/**
 * User service
 */
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;


    /**
     * UserService constructor.
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEM_PER_PAGE
        );
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

}

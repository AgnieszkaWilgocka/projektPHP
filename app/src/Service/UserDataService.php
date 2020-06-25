<?php

/**
 * User data service
 */
namespace App\Service;

use App\Entity\UserData;
use App\Repository\UserDataRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UserDataService
 */
class UserDataService
{
    /**
     * @var UserDataRepository
     */
    private $userDataRepository;

    /**
     * UserDataService constructor.
     *
     * @param UserDataRepository $userDataRepository
     */
    public function __construct(UserDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }

    /**
     * @param UserData $userData
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserData $userData): void
    {
        $this->userDataRepository->save($userData);
    }

}

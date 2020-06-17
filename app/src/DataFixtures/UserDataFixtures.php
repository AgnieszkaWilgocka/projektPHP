<?php

/**
 * UserData fixtures
 */
namespace App\DataFixtures;


use App\Entity\UserData;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserDataFixtures
 */
class UserDataFixtures extends AbstractBaseFixtures
{
    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'usersData', function ($i) {
            $userData = new UserData();
            $userData->setLogin($this->faker->name);

            return $userData;
        });
        $manager->flush();
    }


    /**
     * @return array
     */


}

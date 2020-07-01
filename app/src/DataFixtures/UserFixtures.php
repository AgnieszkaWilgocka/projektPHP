<?php
/**
 * User fixtures.
 */
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Password encoder.
     *
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(5, 'users', function ($i) {

            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([User::ROLE_USER]);
            $user->setUserData($this->getReference('usersData_'.$i));
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'user123'
                )
            );
            //$user->setUsersData($this->getReference('usersData_'.$i));

            return $user;
        });

        $this->createMany(5, 'admins', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setUserData($this->getReference('usersDataAdmin_'.$i));
            $user->setRoles([User::ROLE_ADMIN]);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'admin123'
                )
            );

            return $user;
        });

        $manager->flush();
    }
    /**
     * Return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array Array of dependencies
     */
    public function getDependencies()
    {
        return [UserDataFixtures::class];
    }
}

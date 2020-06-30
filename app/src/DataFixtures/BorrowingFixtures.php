<?php
/**
 * Borrowing fixture
 */
namespace App\DataFixtures;

use App\Entity\Borrowing;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BorrowingFixtures
 */
class BorrowingFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(5, 'borrowings', function ($i) {
            $borrowing = new Borrowing();
            $borrowing->setRecord($this->getRandomReference('records'));
            $borrowing->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1day'));
            $borrowing->setComment($this->faker->sentence);
            $borrowing->setIsExecuted(false);
            $borrowing->setAuthor($this->getRandomReference('users'));

            return $borrowing;
        });

        $manager->flush();
    }

    /**
     * Return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [RecordFixtures::class, UserFixtures::class];
    }
}

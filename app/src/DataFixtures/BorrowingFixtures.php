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
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(5, 'borrowings', function ($i) {
            $borrowing = new Borrowing();
            $borrowing->setRecord($this->getRandomReference('records'));

            return $borrowing;
        });

        $manager->flush();
    }

    /**
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [RecordFixtures::class];
    }
}
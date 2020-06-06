<?php
/**
 * Record fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Record;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RecordFixtures
 */
class RecordFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'records', function ($i) {
            $record = new Record();
            $record->setTitle($this->faker->colorName);
            $record->setCategory($this->getRandomReference('categories'));

            return $record;
        });
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}

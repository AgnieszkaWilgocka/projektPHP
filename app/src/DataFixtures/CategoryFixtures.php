<?php
/**
 * Category fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures
 *
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 30; ++$i) {
            $category = new Category();
            $category->setName($this->faker->sentence);
            $this->manager->persist($category);
        }
        $manager->flush();
    }
}

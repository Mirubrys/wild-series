<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

abstract class AppFixtures extends Fixture
{
    private $referencesIndex = [];

    /** @var ObjectManager */
    private $manager;

    protected $faker;

    protected $populator;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->populator = new Populator($this->faker);
        $this->loadData($manager);
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    protected function getRandomReference(string $className) {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className.'_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Cannot find any references for class "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }
}

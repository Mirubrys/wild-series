<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends AppFixtures implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Lennie James'
    ];

    /**
     * @inheritDoc
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Actor::class, 50, function(Actor $actor, $count) {
            $actor->setName($this->faker->name)
                ->setSlug($this->slugify->generate($actor->getName()))
                ->addProgram($this->getRandomReference(Program::class))
            ;
        });

        foreach (self::ACTORS as $key => $name)
        {
            $actor = new Actor();
            $actor->setName($name)
                ->setSlug($this->slugify->generate($name))
                ->addProgram($this->getReference(Program::class.'_0'))
            ;
            $manager->persist($actor);
            $index = $key+50;
            $this->addReference(Actor::class.'_'.(string)$index, $actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
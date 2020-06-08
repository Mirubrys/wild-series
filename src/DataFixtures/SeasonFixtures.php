<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends AppFixtures implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function loadData(ObjectManager $manager)
    {
        $index = 0;
        foreach (ProgramFixtures::PROGRAMS as $key => $data)
        {
            for ($i=1; $i <= 10; $i++)
            {
                $season = new Season();
                $season->setNumber($i)
                    ->setDescription($this->faker->text(400))
                    ->setYear($this->faker->year($max='now'))
                    ->setProgram($this->getReference(Program::class."_".$key))
                ;
                $manager->persist($season);
                $this->addReference(Season::class.'_'.(string)$index, $season);
                $index++;
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
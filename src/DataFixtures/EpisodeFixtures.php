<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends AppFixtures implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function loadData(ObjectManager $manager)
    {
        $index = 0;
        $seasonNumber = 0;

        foreach (ProgramFixtures::PROGRAMS as $program)
        {
            for ($i=0; $i < 10; $i++)
            {
                for ($j=1; $j<=10; $j++)
                {
                    $episode = new Episode();
                    $episode->setNumber($j)
                        ->setTitle($this->faker->words($nb = 3, $asText = true))
                        ->setSynopsis($this->faker->text(400))
                        ->setSeason($this->getReference(Season::class.'_'.(string)$seasonNumber))
                    ;
                    $manager->persist($episode);
                    $this->addReference(Episode::class.'_'.(string)$index, $episode);
                    $index++;
                }
                $seasonNumber++;
            }
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
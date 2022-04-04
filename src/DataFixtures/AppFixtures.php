<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Location;
use App\Entity\Truck;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 7; $i++)
        {
            $location = new Location();
            if ($i === 0) {
                $location->setDaysOfTheWeekNotAvailable([5]);
            }
            $manager->persist($location);

            $truck = new Truck();
            $manager->persist($truck);
        }

        $manager->flush();
    }
}

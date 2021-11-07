<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Admin;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new Admin();
        $user->setUsername("admin");
        // method n°1
        $user->setPassword($this->passwordEncoder->encodePassword($user, "admin"));
        $manager->persist($user);
        $manager->flush();
    }
}

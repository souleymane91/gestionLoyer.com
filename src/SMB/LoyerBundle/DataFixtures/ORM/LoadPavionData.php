<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Pavion;

class LoadPavionData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $p1 = new Pavion();
        $p2 = new Pavion();
        $p3 = new Pavion();
        $p4 = new Pavion();
        
        //ajout des différents pavions
        $p1->setLibelle("H1");
        $p2->setLibelle("H2");
        $p3->setLibelle("H3");
        $p4->setLibelle("H4");

        //persist des données
        $manager->persist($p1);
        $manager->persist($p2);
        $manager->persist($p3);
        $manager->persist($p4);
        
        $manager->flush();
    }
}

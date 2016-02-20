<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Mois;

class LoadMoisData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $m1 = new Mois();
        $m2 = new Mois();
        $m3 = new Mois();
        $m4 = new Mois();
        $m5 = new Mois();
        $m6 = new Mois();
        $m7 = new Mois();
        $m8 = new Mois();
        $m9 = new Mois();
        $m10 = new Mois();
        $m11 = new Mois();
        $m12 = new Mois();
        
        //ajout des différents mois
        $m1->setLibelle("Janvier");
        $m2->setLibelle("Février");
        $m3->setLibelle("Mars");
        $m4->setLibelle("Avril");
        $m5->setLibelle("Mai");
        $m6->setLibelle("Juin");
        $m7->setLibelle("Juillet");
        $m8->setLibelle("Aout");
        $m9->setLibelle("Septembre");
        $m10->setLibelle("Octobre");
        $m11->setLibelle("Novembre");
        $m12->setLibelle("Decembre");
        
        //persist des données
        $manager->persist($m1);
        $manager->persist($m2);
        $manager->persist($m3);
        $manager->persist($m4);
        $manager->persist($m5);
        $manager->persist($m6);
        $manager->persist($m7);
        $manager->persist($m8);
        $manager->persist($m9);
        $manager->persist($m10);
        $manager->persist($m11);
        $manager->persist($m12);
        $manager->flush();
    }
}

<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Chambre;

class LoadChambreData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        //déclaration des chambres
        $chambre1 = new Chambre();
        $chambre2 = new Chambre();
        $chambre3 = new Chambre();
        $chambre4 = new Chambre();
        $chambre5 = new Chambre();
        $chambre6 = new Chambre();
        $chambre7 = new Chambre();
        $chambre8 = new Chambre();
        $chambre9 = new Chambre();
        $chambre10 = new Chambre();
        $chambre11= new Chambre();
        $chambre12 = new Chambre();
        $chambre13 = new Chambre();
        $chambre14 = new Chambre();
        $chambre15 = new Chambre();
        
        //ajout des numéros  de chambre
        $chambre1->setNumero(1);
        $chambre2->setNumero(2);
        $chambre3->setNumero(3);
        $chambre4->setNumero(4);
        $chambre5->setNumero(5);
        $chambre6->setNumero(6);
        $chambre7->setNumero(7);
        $chambre8->setNumero(8);
        $chambre9->setNumero(9);
        $chambre10->setNumero(10);
        $chambre11->setNumero(11);
        $chambre12->setNumero(12);
        $chambre13->setNumero(13);
        $chambre14->setNumero(14);
        $chambre15->setNumero(15);

        //persist des données
        $manager->persist($chambre1);
        $manager->persist($chambre2);
        $manager->persist($chambre3);
        $manager->persist($chambre4);
        $manager->persist($chambre5);
        $manager->persist($chambre6);
        $manager->persist($chambre7);
        $manager->persist($chambre8);
        $manager->persist($chambre9);
        $manager->persist($chambre10);
        $manager->persist($chambre11);
        $manager->persist($chambre12);
        $manager->persist($chambre13);
        $manager->persist($chambre14);
        $manager->persist($chambre15);
        $manager->flush();
    }
}

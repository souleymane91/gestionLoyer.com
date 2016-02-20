<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Registre;

class LoadRegistreData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $registre = new Registre();
        $registre->setAnneeScolaire("2015-2016");
        $registre->setCaution(9000);
        $date = new \DateTime();
        $registre->setDateFin($date);
        $registre->setMensualite(4000);
        $registre->setMoisDebut("Octobre");
        $registre->setMoisFin("juillet");

        $manager->persist($registre);
        $manager->flush();
    }
}

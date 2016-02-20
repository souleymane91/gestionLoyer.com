<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Etudiant;

class LoadEtudiantData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $etudiant = new Etudiant();
        $etudiant->setPrenom('Souleymane');
        $etudiant->setNom('MBAYE');
        $etudiant->setEmail('souleymanembaye91@gmail.com');
        $etudiant->setTelephone('776409433');

        $manager->persist($etudiant);
        $manager->flush();
    }
}

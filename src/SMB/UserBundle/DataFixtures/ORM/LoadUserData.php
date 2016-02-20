<?php

// src/SMB/UserBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\UserBundle\Entity\User;
use SMB\UserBundle\Entity\Profil;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $profil = new Profil();
        
        $adminUser->setUsername('jules');
        $adminUser->setPrenom('Souleymane');
        $adminUser->setNom('MBAYE');
        $adminUser->setEmail('souleymanembaye91@gmail.com');
        
        $profil->setLibelle("ADMIN");
        $adminUser->addProfil($profil);

        $manager->persist($adminUser);
        $manager->flush();
    }
}



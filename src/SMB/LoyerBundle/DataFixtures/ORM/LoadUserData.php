<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SMB\UserBundle\Entity\User;
use SMB\UserBundle\Entity\Profil;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
        
    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }

        
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
        
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($adminUser)
        ;
        $adminUser->setPassword($encoder->encodePassword('diama', $adminUser->getSalt()));

        $manager->persist($adminUser);
        $manager->flush();
    }
}

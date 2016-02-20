<?php

// src/SMB/LoyerBundle/DataFixtures/ORM/LoadUserData.php

namespace SMB\LoyerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMB\LoyerBundle\Entity\Paiement;

class LoadPaiementData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    }
}

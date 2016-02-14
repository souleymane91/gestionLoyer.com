<?php
// src/Blogger/BlogBundle/Tests/Entity/BlogTest.php

namespace SMB\LoyerBundle\Tests\Entity;

use Doctrine\Common\ClassLoader;

require_once dirname(__FILE__) . '/../../Entity/Etudiant.php';
require_once dirname(__FILE__) . '/../../Entity/Codification.php';

class EtudiantTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPrenom(){
                
        $etudiant = new \SMB\LoyerBundle\Entity\Etudiant();
        $etudiant->setPrenom("Souleymane");
        $etudiant->setNom("MBAYE");
        $etudiant->setTelephone(776409433);
        $etudiant->setEmail("souleymanembaye91@gmail.com");
        $etudiant->setCaution(1);
        
        $this->assertEquals('Souleymane',$etudiant->getPrenom());
        $this->assertTrue($etudiant->setPrenom('125555'));
        
    } 
}
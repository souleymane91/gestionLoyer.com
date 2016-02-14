<?php
// src/Blogger/BlogBundle/Tests/Entity/BlogTest.php

namespace SMB\LoyerBundle\Tests\Entity;

use SMB\LoyerBundle\Entity\Etudiant;

class EtudiantTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPrenom(){
        
        $etudiant = new Etudiant();

        $this->assertEquals('souleymane', $etudiant->setPrenom('souleymane'));
    }
    
}
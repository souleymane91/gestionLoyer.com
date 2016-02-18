<?php

namespace SMB\LoyerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Symfony\Component\Validator\Validator;

class LoyerControllerTest extends WebTestCase{
    
    public function testEtudiantForm(){
        
        $client = static::createClient();
        
        $crawler = $client->request('GET', 'gestionLoyer/web/app_dev.php/gestion-loyer/etudiant/add/');
        
        $this->assertGreaterThan(0, $crawler->filter('html:contains("submit")')->count());
        
        $bouton = $crawler->selectButton('submit');
        
        print_r($bouton);
        /*
        
        $bouton = $crawler->selectButton('smb_loyerbundle_etudiant_enregistrer');
     
        //on crée un objet test de type étudiant
        $etudiant = new \SMB\LoyerBundle\Entity\Etudiant();
        $etudiant->setCaution(true);
        $etudiant->setEmail('souleymanembaye91@gmail.com');
        $etudiant->setNom('MBAYE');
        $etudiant->setPrenom('Souleymane');
        $etudiant->setTelephone('776409433');
        
        
       /* $donnee = array('smb_loyerbundle_etudiant[prenom]' => $etudiant->getPrenom(),
                        'smb_loyerbundle_etudiant[nom]' => $etudiant->getNom(),
                        'smb_loyerbundle_etudiant[email]' => $etudiant->getEmail(),
                        'smb_loyerbundle_etudiant[telephone]' => $etudiant->getTelephone(),
                        'smb_loyerbundle_etudiant[caution]' => $etudiant->getCaution()
                );
        
        $form = $bouton->form($donnee,'POST');
        
        //on valide le formulaire
        $client->submit($form);*/
        
    }    
}

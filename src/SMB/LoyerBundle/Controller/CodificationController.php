<?php
//src\SMB\LoyerBundle\Controller\CodificationController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Config\Definition\Exception\Exception;

use SMB\LoyerBundle\Entity\Codification;
use SMB\LoyerBundle\Form\CodificationType;

class CodificationController extends Controller{

	/***********************************************************
	 * l'action index qui permet de lister toutes les codifications
	 ***********************************************************/
	
	public function indexAction(Request $request){

            $listCodifications = Codification::listCodifications($this);

            return $this->render("SMBLoyerBundle:Codification:index.html.twig",array(
                'listCodifications' => $listCodifications
            ));
	}

	/****************************************************************************
	 l'action add qui permet d'ajouter un nouvelle codification
	 **********************************************************************/
	
	public function addAction(Request $request){
            //création de l'objet codification
            $codification = new Codification();
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                $requete = $request->request->get('requete');
                if($requete == "affichage"){
                    //création du formulaire d'ajout d'une codification
                    $form = $this->get('form.factory')->create(new CodificationType(),$codification);
                    return $this->render("SMBLoyerBundle:Codification:add.html.twig",array(
                        'form' => $form->createView()
                    ));
                }
                else{
                    if($requete == "ajout"){
                        $id_chambre = $request->request->get('numero');
                        $id_pavion = $request->request->get('pavion');
                        $id_etudiant = $request->request->get('id_etudiant');
                        
                        $em = $this->getDoctrine()
                                   ->getManager();
                        
                        //on recupere la chambre correspondant à ce numero
                        $chambre = \SMB\LoyerBundle\Entity\Chambre::getChambre($em, $id_chambre);
                        
                        //on recupere le pavion correspondant à ce nom
                        $pavion = \SMB\LoyerBundle\Entity\Pavion::getPavion($em, $id_pavion);
                        
                        //on recupere l'etudiant correspondant à $id_etudiant
                        $etudiant = \SMB\LoyerBundle\Entity\Etudiant::getEtudiant($em, $id_etudiant);
                        
                        //on recupère le registre courant
                        $session=$request->getSession();
                        $id_registre=$session->get('id_registre_courant');
                        $registre = \SMB\LoyerBundle\Entity\Registre::getRegistre($em,$id_registre);
                        if($registre == null){
                            throw new \Exception("Il n'existe pas de registre correspondant à l'id ".$id_registre);
                        }
                        
                        $codification->setRegistre($registre);
                        $codification->setChambre($chambre);
                        $codification->setPavion($pavion);
                        $codification->setEtudiant($etudiant);
                     
                        $em->persist($codification);
                        $em->flush();

                        //on envoie les informatons de l'étudiant

                        return $this->render("SMBLoyerBundle:Etudiant:view.html.twig",array(
                            'etudiant' => $etudiant,
                            'codifier' => true,
                            'codification' => $codification,
                            'listPaiements'  => $codification->getPaiements()
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
	}
        
	/**********************************************************************
	 l'action edit qui permet de modifier les informations d'une codification
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //création de l'objet codification
            $codification = new Codification();
            //on recupère l'objet codification à editer
            $codification = $this->getDoctrine()
                           ->getManager()
                           ->getRepository("SMBLoyerBundle:Codification")
                           ->find($id);
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                //on recupère la le type de la requete
                $requete = $request->request->get('requete');
                //si on veut afficher le formulaire de modification
                if($requete == "affichage"){
                    //création du formulaire de modification d'un codification
                    $form = $this->get('form.factory')->create(new CodificationType(),$codification);                    
                    return $this->render("SMBLoyerBundle:Codification:edit.html.twig",array(
                        'id' => $id,
                        'form' => $form->createView()
                    ));
                }
                else{
                    //si on veut modifier un codification
                    if($requete == "modification"){
                        $numero = $request->request->get('numero');
                        $codification->setNumero($numero);
                        $em = $this->getDoctrine()
                                   ->getManager();
                        $em->flush();                   

                        //on envoie la liste des codifications
                        $listCodifications = Codification::listCodifications($this);

                        return $this->render("SMBLoyerBundle:Codification:index.html.twig",array(
                            'listCodifications' => $listCodifications
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer une codification
         *********************************************************************/
        public function deleteAction($id,Request $request){
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                //on recupère la liste des codifications à supprimer
                $listCodifications = json_decode($request->request->get('listCodifications'));
                $nbre = $request->request->get('nbre');
                
                for ($i = 0; $i<$nbre; $i++){
                    $ident = $listCodifications[$i];
                    $this->getDoctrine()
                         ->getManager()
                         ->getRepository("SMBLoyerBundle:Codification")
                         ->supprimer_codification($ident);
                }
                //on retourne la liste de tous les codifications
                $listCodification = Codification::listCodifications($this);
                return $this->render("SMBLoyerBundle:Codification:index.html.twig",array(
                    'listCodifications' => $listCodification
                ));
            }
            else{
                throw new \Exception("Pas de requête!",1);
            }
        }

}

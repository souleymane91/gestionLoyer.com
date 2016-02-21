<?php
//src\SMB\LoyerBundle\Controller\ChambreController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use SMB\LoyerBundle\Entity\Chambre;
use SMB\LoyerBundle\Form\ChambreType;

class ChambreController extends Controller{

	/***********************************************************
	 * l'action index qui permet de lister toutes les chambres
	 ***********************************************************/
	
	public function indexAction(Request $request){

            $listChambres = Chambre::listChambres($this);

            return $this->render("SMBLoyerBundle:Chambre:index.html.twig",array(
                'listChambres' => $listChambres
            ));
	}

	/****************************************************************************
	 l'action add qui permet d'ajouter un nouveau chambre
	 **********************************************************************/
	
	public function addAction(Request $request){
            //création de l'objet chambre
            $chambre = new Chambre();
            
            //création du formulaire d'ajout d'un chambre
            $form = $this->get('form.factory')->create(new ChambreType(),$chambre);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet chambre
                    $em->persist($chambre);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage de la chambre
                    return $this->redirect($this->generateUrl('smb_chambre_view',array(
                                                                                    'id' => $chambre->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Chambre:add.html.twig");
	}
        
	/**********************************************************************
	 l'action edit qui permet de modifier les informations d'une chambre
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //on recupère la chambre correspondant à $id
            $chambre = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("SMBLoyerBundle:Chambre")
                             ->find($id);
            
            //création du formulaire à partir de l'objet à modifier
            $form = $this->get('form.factory')->create(new ChambreType(),$chambre);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet chambre
                    $em->persist($chambre);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage de la chambre
                    return $this->redirect($this->generateUrl('smb_chambre_view',array(
                                                                                    'id' => $chambre->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Chambre:add.html.twig");
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer une chambre
         *********************************************************************/
        public function deleteAction($id,Request $request){
            
            $this->getDoctrine()
                 ->getManager()
                 ->getRepository("SMBLoyerBundle:Chambre")
                 ->supprimer_chambre($id);
            
            //on redirige vers la page d'affichage de toutes les chambres
            $this->redirect($this->generateUrl("smb_chambre_home"));
        }

}

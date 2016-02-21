<?php
//src\SMB\LoyerBundle\Controller\PavionController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use SMB\LoyerBundle\Entity\Pavion;
use SMB\LoyerBundle\Form\PavionType;

class PavionController extends Controller{

	/***********************************************************
	 * l'action index qui permet de lister tous les pavions
	 ***********************************************************/
	
	public function indexAction(Request $request){

            $listPavions = Pavion::listPavions($this);

            return $this->render("SMBLoyerBundle:Pavion:index.html.twig",array(
                'listPavions' => $listPavions
            ));
            
	}

	/****************************************************************************
	 l'action add qui permet d'ajouter un nouveau pavion
	 **********************************************************************/
	
	public function addAction(Request $request){
            //création de l'objet pavion
            $pavion = new Pavion();
            
            //création du formulaire d'ajout d'un pavion
            $form = $this->get('form.factory')->create(new PavionType(),$pavion);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet pavion
                    $em->persist($pavion);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du pavion
                    return $this->redirect($this->generateUrl('smb_loyer_parametre',array(
                                                                                    'id' => $pavion->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Pavion:add.html.twig",array(
                'form' => $form->createView()
            ));
	}
        
	/**********************************************************************
	 l'action edit qui permet de modifier les informations d'un pavion
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //on recupère le pavion correspondant à $id
            $pavion = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("SMBLoyerBundle:Pavion")
                             ->find($id);
            
            //création du formulaire à partir de l'objet à modifier
            $form = $this->get('form.factory')->create(new PavionType(),$pavion);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet pavion
                    $em->persist($pavion);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du pavion
                    return $this->redirect($this->generateUrl('smb_pavion_view',array(
                                                                                    'id' => $pavion->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Pavion:add.html.twig");
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer un pavion
         *********************************************************************/
        public function deleteAction($id,Request $request){
            
            $this->getDoctrine()
                 ->getManager()
                 ->getRepository("SMBLoyerBundle:Pavion")
                 ->supprimer_pavion($id);
            
            //on redirige vers la page d'affichage de tous les pavions
            $this->redirect($this->generateUrl("smb_pavion_home"));
        }

}

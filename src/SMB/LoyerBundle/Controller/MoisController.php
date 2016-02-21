<?php
//src\SMB\LoyerBundle\Controller\MoisController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use SMB\LoyerBundle\Entity\Mois;
use SMB\LoyerBundle\Form\MoisType;

class MoisController extends Controller{

	/***********************************************************
	 * l'action index qui permet de lister tous les mois
	 ***********************************************************/
	
	public function indexAction(Request $request){

            $listMois = Mois::listMois($this);

            return $this->render("SMBLoyerBundle:Mois:index.html.twig",array(
                'listMois' => $listMois
            ));
	}

	/****************************************************************************
	 l'action add qui permet d'ajouter un nouveau mois
	 **********************************************************************/
	
	public function addAction(Request $request){
            //création de l'objet mois
            $mois = new Mois();
            
            //création du formulaire d'ajout d'un mois
            $form = $this->get('form.factory')->create(new MoisType(),$mois);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet mois
                    $em->persist($mois);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du mois
                    return $this->redirect($this->generateUrl('smb_mois_view',array(
                                                                                    'id' => $mois->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Mois:add.html.twig");
	}
        
	/****************************************************************************
	 l'action edit qui permet de modifier les informations d'un mois
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //on recupère le rigistre correspondant à $id
            $mois = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("SMBLoyerBundle:Mois")
                             ->find($id);
            
            //création du formulaire à partir de l'objet à modifier
            $form = $this->get('form.factory')->create(new MoisType(),$mois);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet mois
                    $em->persist($mois);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du mois
                    return $this->redirect($this->generateUrl('smb_mois_view',array(
                                                                                    'id' => $mois->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Mois:add.html.twig");
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer un mois
         *********************************************************************/
        public function deleteAction($id,Request $request){
            
            $this->getDoctrine()
                 ->getManager()
                 ->getRepository("SMBLoyerBundle:Mois")
                 ->supprimer_mois($id);
            
            //on redirige vers la page d'affichage de tous les mois
            $this->redirect($this->generateUrl("smb_mois_home"));
        }

}


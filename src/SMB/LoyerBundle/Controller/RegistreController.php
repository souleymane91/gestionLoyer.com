<?php
//src\SMB\LoyerBundle\Controller\RegistreController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use SMB\LoyerBundle\Entity\Registre;
use SMB\LoyerBundle\Form\RegistreType;

class RegistreController extends Controller{

	/***********************************************************
	 * l'action index qui permet de lister tous les registres
	 ***********************************************************/
	
	public function indexAction(Request $request){

            $listRegistres = Registre::listRegistres($this);

            $this->render("SMBLoyerBundle:Registre:index.html.twig",array(
                'listRegistres' => $listRegistres
            ));
            
	}

	/****************************************************************************
	 l'action add qui permet d'ajouter un nouveau registre
	 **********************************************************************/
	
	public function addAction($request){
            //création de l'objet registre
            $registre = new Registre();
            
            //création du formulaire d'ajout d'un registre
            $form = $this->get('form.factory')->create(new RegistreType(),$registre);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet registre
                    $em->persist($registre);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du registre
                    return $this->redirect($this->generateUrl('smb_registre_view',array(
                                                                                    'id' => $registre->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Registre:add.html.twig");
	}
        
	/****************************************************************************
	 l'action edit qui permet de modifier les informations d'un registre
	 **********************************************************************/
	
	public function editAction($id,$request){
            
            //on recupère le rigistre correspondant à $id
            $registre = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("SMBLoyerBundle:Registre")
                             ->find($id);
            
            //création du formulaire à partir de l'objet à modifier
            $form = $this->get('form.factory')->create(new RegistreType(),$registre);
            $form->handleRequest($request);
            //on vérifie si des données ont été postées
            if($request->getMethod() == "POST"){
                //on teste si les données entrées sont valides
                if($form->isValid()){
                    $em = $this->getDoctrine()
                               ->getManager();
                    //on persiste l'objet registre
                    $em->persist($registre);
                    //on enregistre dans la base
                    $em->flush();
                    
                    //on redirige vers la page d'affichage du registre
                    return $this->redirect($this->generateUrl('smb_registre_view',array(
                                                                                    'id' => $registre->getId()
                                                                                    )
                    ));
                }
                //les données saisies ne sont pas valides
            }
            //Pas de données postées, on affiche le formulaire d'ajout
            return $this->render("SMBLoyerBundle:Registre:add.html.twig");
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer un registre
         *********************************************************************/
        public function deleteAction($id,$request){
            
            $this->getDoctrine()
                 ->getManager()
                 ->getRepository("SMBLoyerBundle:Registre")
                 ->supprimer_registre($id);
            
            //on redirige vers la page d'affichage de tous les registres
            $this->redirect($this->generateUrl("smb_registre_home"));
        }

}


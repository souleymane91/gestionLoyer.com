<?php
//src\SMB\LoyerBundle\Controller\LoyerController.php

namespace SMB\LoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use SMB\LoyerBundle\Entity\Etudiant;
use SMB\LoyerBundle\Form\EtudiantType;
use SMB\LoyerBundle\Entity\Codification;
use SMB\LoyerBundle\Form\CodificationType;
use SMB\LoyerBundle\Entity\Registre;
use SMB\LoyerBundle\Form\RegistreType;
use SMB\LoyerBundle\Entity\Paiement;
use SMB\LoyerBundle\Form\PaiementType;
use SMB\LoyerBundle\Entity\Mois;
use SMB\UtilisateurBundle\Entity\Utilisateur;
use SMB\UtilisateurBundle\Form\AuthentificationType;

class LoyerController extends Controller{

	/***********************************************************
	 * l'action index qui est l'accueil de l'application
	 *********************************************************/
	
	public function indexAction($page, Request $request){

		if($page<1){

			throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		//initialisation des variables de session
		$session=$request->getSession();

		//recupération du registre courant
		$registre=new Registre();
		$registre=$this->getDoctrine()
                               ->getManager()
                               ->getRepository("SMB\LoyerBundle\Entity\Registre")
                               ->dernier_registre();

		if($registre!=Null){

			$session->set('id_registre_courant',$registre->getId());
		}

		return $this->render("SMBLoyerBundle:Loyer:index.html.twig");
	}

	/****************************************************************************
	 l'action recherche qui permet de rechercher un étudiant
	 **********************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function rechercheAction(){

		return $this->render("SMBLoyerBundle:Loyer:recherche.html.twig");
	}


	/****************************************************************************
	 l'action codification qui permet d'ajouter une codification à un étudiant
	 ****************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function codificationAction($id,Request $request){

		$etudiant=new Etudiant();
		$etudiant=$this->getDoctrine()
                               ->getManager()
                               ->getRepository("SMBLoyerBundle:Etudiant")
                               ->find($id);

		//on recupère le registre courant
		$session=$request->getSession();
		$id_registre=$session->get('id_registre_courant');
		$registre=new Registre();
		$registre=$this->getDoctrine()
                               ->getManager()
                               ->getRepository("SMB\LoyerBundle\Entity\Registre")
                               ->find($id_registre);

		$codification=new Codification();
		$codification->setEtudiant($etudiant);
		$codification->setRegistre($registre);
		$form=$this->get('form.factory')
                           ->create(new CodificationType(), $codification);

		$form->handleRequest($request);
		if($form->isValid()){
			$em=$this->getDoctrine()
                                 ->getManager();
			$em->persist($codification);
			$em->flush();

			return $this->redirect($this->generateUrl("smb_loyer_view_etudiant",
                            array('id' => $id)
			));
		}

		return $this->render("SMBLoyerBundle:Loyer:codification.html.twig", 
			array('etudiant' => $etudiant, 'form' => $form->createView()
		));
	}



	/****************************************************************************
	 l'action paiement qui permet d'effectuer un paiement pour un étudiant
	 **************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function paiementAction($id,Request $request){

		//on recupère l'objet étudiant correspondant à l'id
		$etudiant=new Etudiant();
		$etudiant=$this->getDoctrine()
					   ->getManager()
					   ->getRepository("SMB\LoyerBundle\Entity\Etudiant")
					   ->find($id);
		
		//on recupère le registre courant
		$session=$request->getSession();
		$id_registre=$session->get('id_registre_courant');
		$registre=new Registre();
		$registre=$this->getDoctrine()
					   ->getManager()
					   ->getRepository("SMB\LoyerBundle\Entity\Registre")
					   ->find($id_registre);
		
		//On recupère la codification de l'etudiant pour le registre courant
		$codification=new Codification();
		$codification=$this->getDoctrine()
						   ->getManager()
						   ->getRepository("SMB\LoyerBundle\Entity\Codification")
						   ->codification_etudiant($etudiant,$registre);
	 		
		if(!is_null($codification)){
			$codifier=true;
 		//on crée l'objet paiement pour lui assigner la codification correspondante
			$paiement=new Paiement();
			$paiement->setCodification($codification);
			$form=$this->get('form.factory')->create(new PaiementType(), $paiement);
			$form->handleRequest($request);
			if($form->isValid()){
				$em=$this->getDoctrine()->getManager();
				$em->persist($paiement);
				$em->flush();

				return $this->redirect($this->generateUrl("smb_loyer_view_etudiant", 
					array('id'=>$etudiant->getId(),
					      'codifier' => $codifier)
				));
			}
			return $this->render('SMBLoyerBundle:Loyer:paiement.html.twig',
				array('etudiant' => $etudiant, 'form'=>$form->createView()
			));				
		}
		
		$codifier=false;
		return $this->redirect($this->generateUrl('smb_loyer_view_etudiant',
			array('id' => $etudiant->getId(),
				  'codifier' => $codifier)
			));
	}

	/****************************************************************************
	 l'action parametre qui permet de gérer le paramètrage de l'application
	 ****************************************************************************/
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function parametreAction(Request $request){
                
            return $this->render("SMBLoyerBundle:Loyer:parametre.html.twig");
	}

}
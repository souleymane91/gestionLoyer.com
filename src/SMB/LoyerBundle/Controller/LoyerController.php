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
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
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


	/***********************************************************
	 * l'action indexEtudiant qui affiche la liste de tous 
	 * les étudiants enregistrés
	 *********************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function indexEtudiantAction(){

		$listEtudiants=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Etudiant")->findAll();

		return $this->render("SMBLoyerBundle:Loyer:indexEtudiant.html.twig", 
			array('listEtudiants' => $listEtudiants
		));
	}


	/****************************************************************************
	l'action view qui s'occupe de l'affichage des informations d'un étudiant
	**************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function viewEtudiantAction($id, Request $request){

		$etudiant= new Etudiant();
		$etudiant=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Etudiant")->find($id);

		//on recupère le registre courant
		$session=$request->getSession();
		$id_registre=$session->get('id_registre_courant');
		$registre=$this->getDoctrine()
						->getManager()
						->getRepository("SMBLoyerBundle:Registre")
						->dernier_registre();
		//Trouvons la codification correspondante au registre actuel
		$codification=$this->getDoctrine()
							->getManager()
							->getRepository("SMBLoyerBundle:Codification")
							->codification_etudiant($etudiant,$registre);

		//liste des paiement correspondant à cette codification
		if(!is_null($codification)){
			$codifier=true;
			$listPaiements=$this->getDoctrine()
								->getManager()
								->getRepository("SMBLoyerBundle:Paiement")
								->paiements($codification);	

			//Trouver la liste composée par les mois correspondant à chaque paiement
			$listMoisPaye=array();
			if(!is_null($listPaiements)){
				foreach ($listPaiements as $paiement) {		
					$listMois=$this->getDoctrine()
										->getManager()
										->getRepository("SMBLoyerBundle:Mois")
										->mois_paye($paiement);
					foreach ($listMois as $mois) {
						$listMoisPaye[]=$mois;
					}
				}		
				//liste des mois non payés
				$nonPaye=array();
				$tableauMois=array();
				$mois=$this->getDoctrine()
				 		   ->getManager()
				 		   ->getRepository("SMBLoyerBundle:Mois")
				 		   ->findAll();
				foreach ($listMoisPaye as $m) {
					$tableauMois[]=$m->getLibelle();
				}
				foreach ($mois as $value) {
					if(!in_array($value->getLibelle(), $tableauMois)){
						$nonPaye[]=$value;
					}					
				}

				//on recupère la liste de tous les mois
				$listmois=$this->getDoctrine()
							   ->getManager()
							   ->getRepository("SMBLoyerBundle:Mois")
							   ->findAll();

				return $this->render("SMBLoyerBundle:Loyer:viewEtudiant.html.twig", 
					array('etudiant' => $etudiant,
						  'codification' => $codification,
						  'listMoisPaye' => $listMoisPaye,
						  'codifier' => $codifier,
						  'nonPaye' => $nonPaye,
						  'listmois' => $listmois
				));
			}
		}

		$codifier=false;
		return $this->render("SMBLoyerBundle:Loyer:viewEtudiant.html.twig", 
					array('etudiant' => $etudiant,
						  'codifier' => $codifier
				));

	}


	/****************************************************************************
	 l'action add qui permet d'ajouter un nouvel étudiant
	 ************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function addEtudiantAction(Request $request){

		//Création de l'objet Etudiant
		$etudiant= new Etudiant();

		//Création du formulaire
		$form=$this->get('form.factory')->create(new EtudiantType(), $etudiant);

		$form->handleRequest($request);
                
                if($request->getMethod()=="POST"){
                    //on enregistre les données tapées par le visiteur en testant si elles sont valides
                    if($form->isValid()){
                            $em=$this->getDoctrine()->getManager();
                            $em->persist($etudiant);
                            $em->flush();

                            return $this->redirect($this->generateUrl("smb_loyer_view_etudiant",array('id'=>$etudiant->getId())));
                    }   
                }
                
		return $this->render("SMBLoyerBundle:Loyer:addEtudiant.html.twig",
			array('form' => $form->createView()
		));
	}


	/****************************************************************************
	 l'action edit qui permet d'éditer les informations d'un étudiant
	 ************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function editEtudiantAction($id, Request $request){

		$etudiant= new Etudiant();
		$etudiant=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Etudiant")->find($id);

		$form= $this->get('form.factory')->create(new EtudiantType(), $etudiant);
		$form->handleRequest($request);
		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->flush();

			return $this->redirect($this->generateUrl("smb_loyer_view_etudiant",
				array('id'=>$etudiant->getId())
			));
		}

		return $this->render("SMBLoyerBundle:Loyer:editEtudiant.html.twig", 
			array('form' => $form->createView()
		));
	}

	/***************************************************************
	* l'action deleteEtudiantAction pour supprimer un étudiant
	***************************************************************/
	public function deleteEtudiantAction($id){

		$etudiant=$this->getDoctrine()
				          ->getManager()
				 		  ->getRepository("SMBLoyerBundle:Etudiant")
				 		  ->find($id);

		if(!is_null($etudiant)){
			$em=$this->getDoctrine()
					 ->getManager();
			$em->remove($etudiant);
			$em->flush();
		}

		//s'il n'existe aucun utilisateur correspondant à $id 
		//on affiche la liste des utilisateurs
		return $this->redirect($this->generateUrl('smb_loyer_home_etudiant'));
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
		$form=$this->get('form.factory')->create(new CodificationType(), $codification);

		$form->handleRequest($request);
		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
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

	/******************************************************************************
	 * l'action indexRegistre qui affiche la liste de tous les registres existants
	 ******************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function indexRegistreAction(){

		$listRegistres=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Registre")->findAll();

		return $this->render("SMBLoyerBundle:Loyer:indexRegistre.html.twig", 
			array('listRegistres' => $listRegistres
		));
	}


	 /****************************************************************************
	 l'action addRegistre qui permet de créer un nouveau registre
	 ************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function addRegistreAction(Request $request){

		//Création de l'objet Registre
		$registre= new Registre();

		//Création du formulaire
		$form=$this->get('form.factory')->create(new RegistreType(), $registre);

		$form->handleRequest($request);

		//on enregistre les données tapées par le visiteur en testant si elles sont valides
		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->persist($registre);
			$em->flush();

			return $this->redirect($this->generateUrl("smb_loyer_view_registre",array('id'=>$registre->getId())));
		}

		return $this->render("SMBLoyerBundle:Loyer:addRegistre.html.twig",
			array('form' => $form->createView()
		));
	}


	/****************************************************************************
	 l'action editRegistre qui permet de modifier les informations d'un registre
	 ****************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function editRegistreAction($id, Request $request){

		$registre= new Registre();
		$registre=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Registre")->find($id);

		$form= $this->get('form.factory')->create(new RegistreType(), $registre);
		$form->handleRequest($request);
		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->flush();

			return $this->redirect($this->generateUrl("smb_loyer_view_registre",
				array('id'=>$registre->getId())
			));
		}

		return $this->render("SMBLoyerBundle:Loyer:editRegistre.html.twig", 
			array('form' => $form->createView()
		));
	}


	/****************************************************************************
	 l'action viewRegistre qui permet d'afficher les informations d'un registre
	 ****************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function viewRegistreAction($id, Request $request){

		$registre= new Registre();
		$registre=$this->getDoctrine()->getManager()->getRepository("SMBLoyerBundle:Registre")->find($id);

		return $this->render("SMBLoyerBundle:Loyer:viewRegistre.html.twig", 
			array('registre' => $registre
		));
	}


	/****************************************************************************
	 l'action admin qui permet de gérer la partie adminstration de l'application
	 ****************************************************************************/
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function adminAction(Request $request){
		return $this->render("SMBLoyerBundle:Loyer:admin.html.twig");
	}

}
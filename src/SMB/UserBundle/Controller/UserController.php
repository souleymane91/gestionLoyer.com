<?php
//SMB\UserBundle\Controller\UserController.php

namespace SMB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Assetic\Exception\Exception;

use SMB\UserBundle\Entity\User;
use SMB\UserBundle\Form\UserType;

class UserController extends Controller
{
	/*************************************************************
	* l'action indexAction qui permet d'afficher la liste de 
	* tous les utilisateurs
	**************************************************************/
	public function indexAction(){

		//on recupère la liste de tous les utilisateurs
		$listUtilisateurs = User::listUtilisateurs($this);

		return $this->render("SMBUserBundle:User:index.html.twig",array(
			'listUtilisateurs' => $listUtilisateurs));
	}
	
	/***********************************************************
	* l'action loginAction qui gère l'authentification d'un 
	* utilisateur
	************************************************************/
	public function loginAction(Request $request)
	{
		// Si le visiteur est déjà identifié, on le redirige vers l'accueil
	    if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
	      return $this->redirectToRoute('smb_stock_home');
	    }
	    
	    // Le service authentication_utils permet de récupérer le nom d'utilisateur
	    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
	    // (mauvais mot de passe par exemple)
	    $authenticationUtils = $this->get('security.authentication_utils');

	    return $this->render('SMBUserBundle:User:connexion.html.twig', array(
	      'last_username' => $authenticationUtils->getLastUsername(),
	      'error'         => $authenticationUtils->getLastAuthenticationError(),
	    ));
		
	}


	/***************************************************************
	* l'action viewAction pour visualiser les informations d'un
	* utilisateur
	***************************************************************/
	public function viewAction($id){

		//on recupère l'utilisateur correspondant à $id
		$utilisateur=new User();
		$utilisateur=$this->getDoctrine()
						  ->getManager()
						  ->getRepository("SMBUserBundle:User")
						  ->find($id);

		if(!is_null($utilisateur)){
			return $this->render("SMBUserBundle:User:view.html.twig",
				array('utilisateur' => $utilisateur
			));
		}

		//s'il n'existe aucun utilisateur correspondant à $id 
		//on affiche la liste des utilisateurs
		return $this->redirect($this->generateUrl('smb_user_home'));
	}

	/***************************************************************
	* l'action addAction pour ajouter un nouvel utilisateur
	***************************************************************/
	public function addAction(Request $request){

		if($request->isXmlHttpRequest()){
		
			//on crée l'objet utilisateur
			$utilisateur= new User();

			//création du formulaire pour ajouter un utilisateur
			$form= $this->get('form.factory')->create(new UserType(), $utilisateur);

			return $this->render("SMBUserBundle:User:add.html.twig", 
				array(
					'form' => $form->createView()
			));
		}
		else throw new \Exception("Error Processing Request", 1);
	}

	/***************************************************************
	* l'action ajoutUser pour enregistrer les informations d'un 
	* nouvel utilisateur
	***************************************************************/
	public function addDataAction(Request $request){

		if($request->isXmlHttpRequest()){

			$prenom=$request->request->get('prenom');
			$nom=$request->request->get('nom');
			$login=$request->request->get('login');
			$email=$request->request->get('email');
			$motdepasse=$request->request->get('motdepasse');
			$id_profil=$request->request->get('profil');

			$factory=$this->get('security.encoder_factory');

			$utilisateur=new User();

			$encoder=$factory->getEncoder($utilisateur);

			$utilisateur->setPrenom($prenom);
			$utilisateur->setNom($nom);
			$utilisateur->setUsername($login);
			$utilisateur->setEmail($email);
			$utilisateur->setPassword($encoder->encodePassword($motdepasse, $utilisateur->getSalt()));

			//on recupère le profil correspondant à id_profil
			$profil=$this->getDoctrine()
				         ->getManager()
				 	     ->getRepository("SMBUserBundle:Profil")
				 	     ->find($id_profil);

			$utilisateur->addProfil($profil);
			$roles[]="ROLE_".$profil->getLibelle();
			$utilisateur->setRoles($roles);

			//Nous allons vérifier si les données saisies sont valides
			$validator=$this->get('validator');
			$listErreurs=$validator->validate($utilisateur);
			if(count($listErreurs)>0){
				throw new \Exception("Les données saisies ne sont pas valides", 1);
				
			}
			else{
				$em=$this->getDoctrine()->getManager();
				$em->persist($utilisateur);
				$em->flush();	

				return $this->render("SMBUserBundle:User:view.html.twig",
						array('utilisateur' => $utilisateur
					));
			}

		}
		else throw new \Exception("Aucune requete POST détectée!", 1);
	
	}

	/***************************************************************
	* l'action editAction pour modifier les informations d'un
	* utilisateur
	***************************************************************/
	public function editAction($id, Request $request){

		/* traitement de la requête pour modifier les information d'un utilisateur*/
		if($request->isXmlHttpRequest()){
			$prenom=$request->request->get('prenom');
			$nom=$request->request->get('nom');
			$login=$request->request->get('login');
			$email=$request->request->get('email');
			$motdepasse=$request->request->get('motdepasse');
			$id_profil=$request->request->get('profil');
			echo $id_profil;

			$factory=$this->get('security.encoder_factory');

			$utilisateur=new User();

			$encoder=$factory->getEncoder($utilisateur);

			$utilisateur->setPrenom($prenom);
			$utilisateur->setNom($nom);
			$utilisateur->setUsername($login);
			$utilisateur->setEmail($email);
			$utilisateur->setPassword($encoder->encodePassword($motdepasse, $utilisateur->getSalt()));

			//on recupère le profil correspondant à id_profil
			$profil=$this->getDoctrine()
				         ->getManager()
				 	     ->getRepository("SMBUserBundle:Profil")
				 	     ->find($id_profil);

			$utilisateur->addProfil($profil);
			$roles[]="ROLE_".$profil->getLibelle();
			$utilisateur->setRoles($roles);

			//Nous allons vérifier si les données saisies sont valides
			$validator=$this->get('validator');
			$listErreurs=$validator->validate($utilisateur);
			if(count($listErreurs)>0){
				throw new \Exception("Les données saisies ne sont pas valides", 1);
				
			}
			else{
				$em=$this->getDoctrine()->getManager();
				$em->persist($utilisateur);
				$em->flush();	

				return $this->render("SMBUserBundle:User:view.html.twig",
						array('utilisateur' => $utilisateur
					));
			}
		}
		else{

			$factory=$this->get('security.encoder_factory');

			//on recupère l'utilisateur correspondant à $id
			$utilisateur=new User();

			$encoder=$factory->getEncoder($utilisateur);

			$utilisateur=$this->getDoctrine()
							  ->getManager()
							  ->getRepository("SMBUserBundle:User")
							  ->find($id);
			if(!is_null($utilisateur)){
				$form=$this->get('form.factory')->create(new UserType(), $utilisateur);
				$form->handleRequest($request);
				if($form->isValid()){
					$utilisateur->setPassword($encoder->encodePassword($utilisateur->getPassword(), $utilisateur->getSalt()));
					
					$roles=array();
					foreach ($utilisateur->getProfils() as $profil) {
						$roles[]="ROLE_".$profil->getLibelle();
					}
					
				$utilisateur->setRoles($roles);
					$em=$this->getDoctrine()->getManager();
					$em->persist($utilisateur);
					$em->flush();

					return $this->redirect($this->generateUrl('smb_user_view', array(
															'id' => $utilisateur->getId()
														))
					);
				}

				return $this->render("SMBUserBundle:User:edit.html.twig",
					array('utilisateur' => $utilisateur,
						  'form' => $form->createView()
				));
			}

		

		//s'il n'existe aucun utilisateur correspondant à $id 
		//on affiche la liste des utilisateurs
		return $this->redirect($this->generateUrl('smb_user_home'));
	}
	}

	/***************************************************************
	* l'action deleteAction pour supprimer un utilisateur
	***************************************************************/
	public function deleteAction($id){

                $em=$this->getDoctrine()
                         ->getManager()
                         ->getRepository("SMBUserBundle:User")
                         ->supprimer_utilisateur($id);
		
		//on affiche la liste des utilisateurs
		return $this->redirect($this->generateUrl('smb_user_home'));
	}

}
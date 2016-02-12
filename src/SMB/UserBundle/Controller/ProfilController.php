<?php
//SMB\UserBundle\Controller\ProfilController.php

namespace SMB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use SMB\UserBundle\Entity\Profil;
use SMB\UserBundle\Form\ProfilType;

class ProfilController extends Controller
{
	/*************************************************************
	* l'action indexAction qui permet d'afficher la liste de 
	* tous les profils
	**************************************************************/
	public function indexAction(){

		//on recupère la liste de tous les utilisateurs
		$listProfils=$this->getDoctrine()
						  ->getManager()
				   	      ->getRepository("SMBUserBundle:Profil")
					      ->findAll();

		return $this->render("SMBUserBundle:Profil:index.html.twig",array(
			'listProfils' => $listProfils));
	}

	/***************************************************************
	* l'action viewAction pour visualiser les informations sur un
	* profil
	***************************************************************/
	public function viewAction($id){

		//on recupère le profil correspondant à $id
		$profil=new Profil();
		$profil=$this->getDoctrine()
						  ->getManager()
						  ->getRepository("SMBUserBundle:Profil")
						  ->find($id);

		if(!is_null($profil)){
			return $this->render("SMBUserBundle:Profil:view.html.twig",
				array('profil' => $profil
			));
		}

		//s'il n'existe aucun profil correspondant à $id on affiche la liste des profils
		return $this->redirect($this->generateUrl('smb_profil_home'));
	}

	/***************************************************************
	* l'action addAction pour ajouter un nouveau profil
	***************************************************************/
	public function addAction(Request $request){

		$profil= new Profil();

		//création du formulaire pour ajouter un utilisateur
		$form= $this->get('form.factory')->create(new ProfilType(), $profil);

		$form->handleRequest($request);
		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->persist($profil);
			$em->flush();

			return $this->redirect($this->generateUrl('smb_profil_view', 
													array('id'=> $profil->getId()
											))
									);
		}

		return $this->render("SMBUserBundle:Profil:add.html.twig", 
			array('form' => $form->createView()
		));
	}

	/***************************************************************
	* l'action editAction pour modifier les informations d'un profil
	***************************************************************/
	public function editAction($id, Request $request){

		//on recupère le profil correspondant à $id
		$profil=new Profil();
		$profil=$this->getDoctrine()
						  ->getManager()
						  ->getRepository("SMBUserBundle:Profil")
						  ->find($id);

		if(!is_null($profil)){
			$form=$this->get('form.factory')->create(new ProfilType(), $profil);
			$form->handleRequest($request);
			if($form->isValid()){
				$em=$this->getDoctrine()->getManager();
				$em->persist($profil);
				$em->flush();

				return $this->redirect($this->generateUrl('smb_profil_view', array(
														'id' => $profil->getId()
													))
				);
			}

			return $this->render("SMBUserBundle:Profil:edit.html.twig",
				array('profil' => $profil,
					  'form' => $form->createView()
			));
		}

		//s'il n'existe aucun profil correspondant à $id on affiche la liste des profils
		return $this->redirect($this->generateUrl('smb_profil_home'));
	}

	/***************************************************************
	* l'action deleteAction pour supprimer un profil
	***************************************************************/
	public function deleteAction($id){

		$profil=$this->getDoctrine()
					 ->getManager()
				     ->getRepository("SMBUserBundle:Profil")
				     ->find($id);

		if(!is_null($profil)){

			$em=$this->getDoctrine()
				 	 ->getManager();
			$em->remove($profil);
			$em->flush();
		}

		//s'il n'existe aucun profil correspondant à $id on affiche la liste des profils
		return $this->redirect($this->generateUrl('smb_profil_home'));
	}
}
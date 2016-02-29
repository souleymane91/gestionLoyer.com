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

class EtudiantController extends Controller{
/***********************************************************
	 * l'action indexEtudiant qui affiche la liste de tous 
	 * les étudiants enregistrés
	 *********************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function indexAction(){

		$listEtudiants=  Etudiant::listEtudiants($this);

		return $this->render("SMBLoyerBundle:Etudiant:index.html.twig", 
			array('listEtudiants' => $listEtudiants
		));
	}


	/****************************************************************************
	l'action view qui s'occupe de l'affichage des informations d'un étudiant
	**************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_USER')")
	*/
	public function viewAction($id, Request $request){

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

				return $this->render("SMBLoyerBundle:Etudiant:view.html.twig", 
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
		return $this->render("SMBLoyerBundle:Etudiant:view.html.twig", 
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
	public function addAction(Request $request){

		//Création de l'objet Etudiant
            $etudiant= new Etudiant();
            if($request->isXmlHttpRequest()){
                //on recupère le type de la requete
                $requete = $request->request->get('requete');
                if($requete == "affichage"){//on veut afficher le formulaire d'ajout
                    //Création du formulaire
                    $form=$this->get('form.factory')->create(new EtudiantType(), $etudiant);  

                    return $this->render("SMBLoyerBundle:Etudiant:add.html.twig",array(
                        'form' => $form->createView()
                    ));                        
                }
                else{
                    if($requete == "ajout"){//on veut enregistrer les données dans la base
                        //on recupère les information envoyées par la requete
                        $etudiant->setPrenom($request->request->get('prenom'));
                        $etudiant->setNom($request->request->get('nom'));
                        $etudiant->setEmail($request->request->get('email'));
                        $etudiant->setTelephone($request->request->get('telephone'));

                        $em = $this->getDoctrine()
                                   ->getManager();
                        $errors = array();
                        //vérifions si les données peuvent être enregistrées
                        if($etudiant->mailExiste($this)){//le mail saisi existe déjà
                            $errors['email'] = "Cette adresse mail existe déjà!";
                        }
                        else{
                            $errors['email'] = "";
                            //on enregistre l'etudiant dans la base de données 
                            $em->persist($etudiant);
                            $em->flush();                                
                        }
                        //on envoie la liste de tous les étudiants
                        $listEtudiants = Etudiant::listEtudiants($this);
                        return $this->render("SMBLoyerBundle:Etudiant:index.html.twig",array(
                            'listEtudiants' => $listEtudiants,
                            'errors' => $errors
                        ));                                

                    }
                }
            }
            else{
                throw new \Exception("Erreur! pas de requete.");
            }
	}


	/****************************************************************************
	 l'action edit qui permet d'éditer les informations d'un étudiant
	 ************************************************************************/
	/**
	* @Security("has_role('ROLE_GESTIONNAIRE')")
	*/
	public function editAction($id, Request $request){
            
            //Création de l'objet Etudiant
            $etudiant= new Etudiant();
            if($request->isXmlHttpRequest()){
                //on recupère le type de la requete
                $requete = $request->request->get('requete');
                if($requete == "affichage"){//on veut afficher le formulaire d'ajout
                    //on recupère l'étudiant à modifier
                    $etudiant = $this->getDoctrine()
                                     ->getManager()
                                     ->getRepository("SMBLoyerBundle:Etudiant")
                                     ->find($request->request->get('id_etudiant'));
                    //Création du formulaire
                    $form=$this->get('form.factory')->create(new EtudiantType(), $etudiant);  

                    return $this->render("SMBLoyerBundle:Etudiant:edit.html.twig",array(
                        'form' => $form->createView()
                    ));                        
                }
                else{
                    if($requete == "ajout"){//on veut enregistrer les données dans la base
                        //on recupère les information envoyées par la requete
                        $etudiant->setPrenom($request->request->get('prenom'));
                        $etudiant->setNom($request->request->get('nom'));
                        $etudiant->setEmail($request->request->get('email'));
                        $etudiant->setTelephone($request->request->get('telephone'));

                        $em = $this->getDoctrine()
                                   ->getManager();
                        $errors = array();
                        //vérifions si les données peuvent être enregistrées
                        if($etudiant->mailExiste($this)){//le mail saisi existe déjà
                            $errors['email'] = "Cette adresse mail existe déjà!";
                        }
                        else{
                            $errors['email'] = "";
                            //on enregistre l'etudiant dans la base de données 
                            $em->persist($etudiant);
                            $em->flush();                                
                        }
                        //on envoie la liste de tous les étudiants
                        $listEtudiants = Etudiant::listEtudiants($this);
                        return $this->render("SMBLoyerBundle:Etudiant:index.html.twig",array(
                            'listEtudiants' => $listEtudiants,
                            'errors' => $errors
                        ));                                

                    }
                }
            }
            else{
                throw new \Exception("Erreur! pas de requete.");
            }
	}

	/***************************************************************
	* l'action deleteEtudiantAction pour supprimer un étudiant
	***************************************************************/
	public function deleteAction($id, Request $request){

            if($request->isXmlHttpRequest()){
                //on supprime l'étudiant
                $this->getDoctrine()
                     ->getManager()
                     ->getRepository("SMBLoyerBundle:Etudiant")
                     ->supprimer_etudiant($request->request->get('id_etudiant'));

                //on affiche la liste des utilisateurs
                $listEtudiants = Etudiant::listEtudiants($this);
                return $this->render("SMBLoyerBundle:Etudiant:index.html.twig",array(
                    'listEtudiants' => $listEtudiants
                ));                
            }
            else{
                throw new \Exception("Erreur: aucune requête ajax!");
            }
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

			return $this->redirect($this->generateUrl("smb_etudiant_view",
				array('id' => $id)
			));
		}

		return $this->render("SMBLoyerBundle:Etudiant:codification.html.twig", 
			array('etudiant' => $etudiant, 'form' => $form->createView()
		));
	}
}
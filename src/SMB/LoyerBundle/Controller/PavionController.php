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
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                $requete = $request->request->get('requete');
                if($requete == "affichage"){
                    //création du formulaire d'ajout d'un pavion
                    $form = $this->get('form.factory')->create(new PavionType(),$pavion);
                    return $this->render("SMBLoyerBundle:Pavion:add.html.twig",array(
                        'form' => $form->createView()
                    ));
                }
                else{
                    if($requete == "ajout"){
                        $nom_pavion = $request->request->get('nom_pavion');
                        $pavion->setLibelle($nom_pavion);
                        $em = $this->getDoctrine()
                                   ->getManager();
                        $em->persist($pavion);
                        $em->flush();                   

                        //on envoie la liste des pavions
                        $listPavions = Pavion::listPavions($this);

                        return $this->render("SMBLoyerBundle:Pavion:index.html.twig",array(
                            'listPavions' => $listPavions
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
	}
        
	/**********************************************************************
	 l'action edit qui permet de modifier les informations d'un pavion
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //création de l'objet pavion
            $pavion = new Pavion();
            //on recupère l'objet pavion à editer
            $pavion = $this->getDoctrine()
                           ->getManager()
                           ->getRepository("SMBLoyerBundle:Pavion")
                           ->find($id);
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                //on recupère la le type de la requete
                $requete = $request->request->get('requete');
                //si on veut afficher le formulaire de modification
                if($requete == "affichage"){
                    //création du formulaire de modification d'un pavion
                    $form = $this->get('form.factory')->create(new PavionType(),$pavion);
                    return $this->render("SMBLoyerBundle:Pavion:edit.html.twig",array(
                        'form' => $form->createView()
                    ));
                }
                else{
                    //si on veut modifier un pavion
                    if($requete == "modification"){
                        $nom_pavion = $request->request->get('nom_pavion');
                        $pavion->setLibelle($nom_pavion);
                        $em = $this->getDoctrine()
                                   ->getManager();
                        $em->flush();                   

                        //on envoie la liste des pavions
                        $listPavions = Pavion::listPavions($this);

                        return $this->render("SMBLoyerBundle:Pavion:index.html.twig",array(
                            'listPavions' => $listPavions
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
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

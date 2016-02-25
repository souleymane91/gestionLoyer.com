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
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                $requete = $request->request->get('requete');
                if($requete == "affichage"){
                    //création du formulaire d'ajout d'une chambre
                    $form = $this->get('form.factory')->create(new ChambreType(),$chambre);
                    return $this->render("SMBLoyerBundle:Chambre:add.html.twig",array(
                        'form' => $form->createView()
                    ));
                }
                else{
                    if($requete == "ajout"){
                        $numero = $request->request->get('numero_chambre');
                        $chambre->setNumero($numero);
                        $em = $this->getDoctrine()
                                   ->getManager();
                        //vérifions si ce chambre n'existe pas dans la base de données 
                        if(!$chambre->existe($this)){
                            $existe = false;
                            $em->persist($chambre);
                            $em->flush();
                        }
                        else{//cette chambre est dans la base de données
                            //vérifions si c'est supprimé ou pas
                            if($chambre->estSupprime($this)){
                                //on le restaure
                                $chambre->restaurer($this);
                                $existe = false;
                            }
                            else{//la chambre existe et n'a pas été supprimé
                                $existe = true;                                
                            }
                        }

                        //on envoie la liste des chambres
                        $listChambres = Chambre::listChambres($this);

                        return $this->render("SMBLoyerBundle:Chambre:index.html.twig",array(
                            'listChambres' => $listChambres,
                            'erreur' => $existe
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
	}
        
	/**********************************************************************
	 l'action edit qui permet de modifier les informations d'une chambre
	 **********************************************************************/
	
	public function editAction($id,Request $request){
            
            //création de l'objet chambre
            $chambre = new Chambre();
            //on recupère l'objet chambre à editer
            $chambre = $this->getDoctrine()
                           ->getManager()
                           ->getRepository("SMBLoyerBundle:Chambre")
                           ->find($id);
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                //on recupère la le type de la requete
                $requete = $request->request->get('requete');
                //si on veut afficher le formulaire de modification
                if($requete == "affichage"){
                    //création du formulaire de modification d'un chambre
                    $form = $this->get('form.factory')->create(new ChambreType(),$chambre);                    
                    return $this->render("SMBLoyerBundle:Chambre:edit.html.twig",array(
                        'id' => $id,
                        'form' => $form->createView()
                    ));
                }
                else{
                    //si on veut modifier un chambre
                    if($requete == "modification"){
                        $numero = $request->request->get('numero');
                        $chambre->setNumero($numero);
                        $em = $this->getDoctrine()
                                   ->getManager();
                        $em->flush();                   

                        //on envoie la liste des chambres
                        $listChambres = Chambre::listChambres($this);

                        return $this->render("SMBLoyerBundle:Chambre:index.html.twig",array(
                            'listChambres' => $listChambres
                        ));                        
                    }
                }
            }
            else{
                throw new Exception("Pas de Requete envoyée!",1);
            }
	}
        
        /*********************************************************************
         * l'action delete qui permet de supprimer une chambre
         *********************************************************************/
        public function deleteAction($id,Request $request){
            
            //si nous avons une requête ajax, elle sera traité ici
            if($request->isXmlHttpRequest()){
                //on recupère la liste des chambres à supprimer
                $listChambres = json_decode($request->request->get('listChambres'));
                $nbre = $request->request->get('nbre');
                
                for ($i = 0; $i<$nbre; $i++){
                    $ident = $listChambres[$i];
                    $this->getDoctrine()
                         ->getManager()
                         ->getRepository("SMBLoyerBundle:Chambre")
                         ->supprimer_chambre($ident);
                }
                //on retourne la liste de tous les chambres
                $listChambre = Chambre::listChambres($this);
                return $this->render("SMBLoyerBundle:Chambre:index.html.twig",array(
                    'listChambres' => $listChambre
                ));
            }
            else{
                throw new \Exception("Pas de requête!",1);
            }
        }

}

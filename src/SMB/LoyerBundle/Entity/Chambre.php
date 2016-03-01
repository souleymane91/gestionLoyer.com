<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chambre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\ChambreRepository")
 */
class Chambre
{
    /**
    * codification
    *
    * @ORM\OneToMany(targetEntity="SMB\LoyerBundle\Entity\Codification", mappedBy="chambre")
    * @ORM\JoinColumn(nullable=true)
    */
    private $codifications;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var boolean
     *
     * @ORM\Column(name="supprime", type="boolean")
     */
    private $supprime;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Chambre
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
    
    /**
     * Set supprime
     *
     * @param boolean $supprime
     * @return Chambre
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Get supprime
     *
     * @return boolean 
     */
    public function getSupprime()
    {
        return $this->supprime;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supprime = false;
    }

    /**
     * Add codifications
     *
     * @param \SMB\LoyerBundle\Entity\Codification $codifications
     * @return Chambre
     */
    public function addCodification(\SMB\LoyerBundle\Entity\Codification $codifications)
    {
        $this->codifications[] = $codifications;

        return $this;
    }

    /**
     * Remove codifications
     *
     * @param \SMB\LoyerBundle\Entity\Codification $codifications
     */
    public function removeCodification(\SMB\LoyerBundle\Entity\Codification $codifications)
    {
        $this->codifications->removeElement($codifications);
    }

    /**
     * Get codifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCodifications()
    {
        return $this->codifications;
    }
    
    /******************************************************
     * fonction qui recupère la liste de toutes les chambres
     ******************************************************/
    public static function listChambres(\SMB\LoyerBundle\Controller\ChambreController $cont){
        //on recupère la liste de toutes les chambres
        //qui n'ont pas été supprimés
        $listChambres=$cont->getDoctrine()
                            ->getManager()
                            ->getRepository("SMBLoyerBundle:Chambre")
                            ->listChambres();
        return $listChambres;
    }
    
        
    /***************************************************
     * fonction qui vérifie si la chambre existe ou pas
     ***************************************************/
    public function existe(\SMB\LoyerBundle\Controller\ChambreController $cont){
        $existe = $cont->getDoctrine()
             ->getManager()
             ->getRepository("SMBLoyerBundle:Chambre")
             ->existe($this->numero);
        
            return $existe;
    }
    /******************************************************
     * fonction vérifie si une chambre est supprimée ou pas
     ******************************************************/
    public function estSupprime(\SMB\LoyerBundle\Controller\ChambreController $cont){
        $resultat = $cont->getDoctrine()
                         ->getManager()
                         ->getRepository("SMBLoyerBundle:Chambre")
                         ->estSupprime($this->numero);
        
        return $resultat;
    }
    /*********************************************************
     * fonction qui permet de restaurer une chambre supprimée
     * renvoie true si la chambre à restaurer existe
     * et false sinon
     *********************************************************/
    public function restaurer(\SMB\LoyerBundle\Controller\ChambreController $cont){
        $existe = $cont->getDoctrine()
                       ->getManager()
                       ->getRepository("SMBLoyerBundle:Chambre")
                       ->restaurer($this->numero);
        return $existe;
    }
    
    /***********************************************************
     * fonction qui permet de recuperer la chambre correspondant
     * à un numero de chambre donné
     ***********************************************************/
    public static function getChambre($em, $numero){
        $chambre = $em->getRepository("SMBLoyerBundle:Chambre")
                      ->find($numero);
        return $chambre;
    }
}

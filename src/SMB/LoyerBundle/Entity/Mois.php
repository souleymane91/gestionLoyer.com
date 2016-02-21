<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mois
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\MoisRepository")
 */
class Mois
{
    /**
     * paiement
     *
     * @ORM\ManyToMany(targetEntity="SMB\LoyerBundle\Entity\Paiement", mappedBy="mois")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paiements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

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
     * Set libelle
     *
     * @param string $libelle
     * @return Mois
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    
    /**
     * Set supprime
     *
     * @param boolean $supprime
     * @return Mois
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
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add paiements
     *
     * @param \SMB\LoyerBundle\Entity\Paiement $paiements
     * @return Mois
     */
    public function addPaiement(\SMB\LoyerBundle\Entity\Paiement $paiements)
    {
        $this->paiements[] = $paiements;

        return $this;
    }

    /**
     * Remove paiements
     *
     * @param \SMB\LoyerBundle\Entity\Paiement $paiements
     */
    public function removePaiement(\SMB\LoyerBundle\Entity\Paiement $paiements)
    {
        $this->paiements->removeElement($paiements);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
    
    /******************************************************
     * fonction qui recupère la liste de tous les mois
     ******************************************************/
    public static function listMois(\SMB\LoyerBundle\Controller\MoisController $cont){
        //on recupère la liste de tous les mois
        //qui n'ont pas été supprimés
        $listMois=$cont->getDoctrine()
                            ->getManager()
                            ->getRepository("SMBLoyerBundle:Mois")
                            ->listMois();
        return $listMois;
    }
}

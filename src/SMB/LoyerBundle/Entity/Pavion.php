<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pavion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\PavionRepository")
 */
class Pavion
{
    /**
    * codification
    *
    * @ORM\OneToMany(targetEntity="SMB\LoyerBundle\Entity\Codification", mappedBy="pavion")
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
     * @return Pavion
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
     * @return Pavion
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
     * @return Pavion
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
     * fonction qui recupère la liste de tous les pavions
     ******************************************************/
    public static function listPavions(\SMB\LoyerBundle\Controller\PavionController $cont){
        //on recupère la liste de tous les pavions
        //qui n'ont pas été supprimés
        $listPavions=$cont->getDoctrine()
                            ->getManager()
                            ->getRepository("SMBLoyerBundle:Pavion")
                            ->listPavions();
        return $listPavions;
    }
}

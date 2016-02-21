<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\RegistreRepository")
 */
class Registre
{
    /**
     * codification
     *
     * @ORM\OneToMany(targetEntity="SMB\LoyerBundle\Entity\Codification", mappedBy="etudiant")
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
     * @ORM\Column(name="annee_scolaire", type="string", length=255)
     */
    private $anneeScolaire;

    /**
     * @var string
     *
     * @ORM\Column(name="mois_debut", type="string", length=255)
     */
    private $moisDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="mois_fin", type="string", length=255)
     */
    private $moisFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="mensualite", type="integer")
     */
    private $mensualite;

    /**
     * @var integer
     *
     * @ORM\Column(name="caution", type="integer")
     */
    private $caution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime",nullable=true)
     */
    private $dateFin;
    
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
     * Set anneeScolaire
     *
     * @param string $anneeScolaire
     * @return Registre
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return string 
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set moisDebut
     *
     * @param string $moisDebut
     * @return Registre
     */
    public function setMoisDebut($moisDebut)
    {
        $this->moisDebut = $moisDebut;

        return $this;
    }

    /**
     * Get moisDebut
     *
     * @return string 
     */
    public function getMoisDebut()
    {
        return $this->moisDebut;
    }

    /**
     * Set moisFin
     *
     * @param string $moisFin
     * @return Registre
     */
    public function setMoisFin($moisFin)
    {
        $this->moisFin = $moisFin;

        return $this;
    }

    /**
     * Get moisFin
     *
     * @return string 
     */
    public function getMoisFin()
    {
        return $this->moisFin;
    }

    /**
     * Set mensualite
     *
     * @param integer $mensualite
     * @return Registre
     */
    public function setMensualite($mensualite)
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    /**
     * Get mensualite
     *
     * @return integer 
     */
    public function getMensualite()
    {
        return $this->mensualite;
    }

    /**
     * Set caution
     *
     * @param integer $caution
     * @return Registre
     */
    public function setCaution($caution)
    {
        $this->caution = $caution;

        return $this;
    }

    /**
     * Get caution
     *
     * @return integer 
     */
    public function getCaution()
    {
        return $this->caution;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Registre
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Registre
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
    
    /**
     * Set supprime
     *
     * @param boolean $supprime
     * @return Registre
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

        $this->dateDebut=new \DateTime();
    }

    /**
     * Add codifications
     *
     * @param \SMB\LoyerBundle\Entity\Codification $codifications
     * @return Registre
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
     * fonction qui recupère la liste de tous les registres
     ******************************************************/
    public static function listRegistres(\SMB\LoyerBundle\Controller\RegistreController $cont){
        //on recupère la liste de tous les registres
        //qui n'ont pas été supprimés
        $listRegistres=$cont->getDoctrine()
                            ->getManager()
                            ->getRepository("SMBLoyerBundle:Registre")
                            ->listRegistres();
        return $listRegistres;
    }
}

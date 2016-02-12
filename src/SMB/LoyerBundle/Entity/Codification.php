<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\CodificationRepository")
 */
class Codification
{
    /**
     * etudiant
     *
     * @ORM\ManyToOne(targetEntity="SMB\LoyerBundle\Entity\Etudiant", inversedBy="codifications", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiant;

    /**
     * registre
     *
     * @ORM\ManyToOne(targetEntity="SMB\LoyerBundle\Entity\Registre", inversedBy="codifications", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $registre;

    /**
     * paiement
     *
     * @ORM\OneToMany(targetEntity="SMB\LoyerBundle\Entity\Paiement", mappedBy="codification")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paiements;

    /**
     * pavion
     *
     * @ORM\ManyToOne(targetEntity="SMB\LoyerBundle\Entity\Pavion", inversedBy="codifications", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pavion;

    /**
    * chambre
    *
    * @ORM\ManyToOne(targetEntity="SMB\LoyerBundle\Entity\Chambre", inversedBy="codifications", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $chambre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_codification", type="datetime")
     */
    private $dateCodification;


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
     * Set dateCodification
     *
     * @param \DateTime $dateCodification
     * @return Codification
     */
    public function setDateCodification($dateCodification)
    {
        $this->dateCodification = $dateCodification;

        return $this;
    }

    /**
     * Get dateCodification
     *
     * @return \DateTime 
     */
    public function getDateCodification()
    {
        return $this->dateCodification;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCodification=new \DateTime();
    }

    /**
     * Set etudiant
     *
     * @param \SMB\LoyerBundle\Entity\Etudiant $etudiant
     * @return Codification
     */
    public function setEtudiant(\SMB\LoyerBundle\Entity\Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;

        $etudiant->addCodification($this);

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return \SMB\LoyerBundle\Entity\Etudiant 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set registre
     *
     * @param \SMB\LoyerBundle\Entity\Registre $registre
     * @return Codification
     */
    public function setRegistre(\SMB\LoyerBundle\Entity\Registre $registre)
    {
        $this->registre = $registre;

        $registre->addCodification($this);

        return $this;
    }

    /**
     * Get registre
     *
     * @return \SMB\LoyerBundle\Entity\Registre 
     */
    public function getRegistre()
    {
        return $this->registre;
    }

    /**
     * Add paiements
     *
     * @param \SMB\LoyerBundle\Entity\Paiement $paiements
     * @return Codification
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

    /**
     * Set pavion
     *
     * @param \SMB\LoyerBundle\Entity\Pavion $pavion
     * @return Codification
     */
    public function setPavion(\SMB\LoyerBundle\Entity\Pavion $pavion)
    {
        $this->pavion = $pavion;

        $pavion->addCodification($this);

        return $this;
    }

    /**
     * Get pavion
     *
     * @return \SMB\LoyerBundle\Entity\Pavion 
     */
    public function getPavion()
    {
        return $this->pavion;
    }

    /**
     * Set chambre
     *
     * @param \SMB\LoyerBundle\Entity\Chambre $chambre
     * @return Codification
     */
    public function setChambre(\SMB\LoyerBundle\Entity\Chambre $chambre)
    {
        $this->chambre = $chambre;

        $chambre->addCodification($this);

        return $this;
    }

    /**
     * Get chambre
     *
     * @return \SMB\LoyerBundle\Entity\Chambre 
     */
    public function getChambre()
    {
        return $this->chambre;
    }
}

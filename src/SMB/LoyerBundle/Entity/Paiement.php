<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\PaiementRepository")
 */
class Paiement
{
    /**
     * codification
     *
     * @ORM\ManyToOne(targetEntity="SMB\LoyerBundle\Entity\Codification", inversedBy="paiements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $codification;

    /**
     * mois
     *
     * @ORM\ManyToMany(targetEntity="SMB\LoyerBundle\Entity\Mois", inversedBy="paiements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mois;

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
     * @ORM\Column(name="date_paiement", type="datetime")
     */
    private $datePaiement;


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
     * Set datePaiement
     *
     * @param \DateTime $datePaiement
     * @return Paiement
     */
    public function setDatePaiement($datePaiement)
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime 
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }
    
    public function __construct()
    {
        $this->mois = new \Doctrine\Common\Collections\ArrayCollection();
        $this->datePaiement=new \DateTime();
    }

    /**
     * Add mois
     *
     * @param \SMB\LoyerBundle\Entity\Mois $mois
     * @return Paiement
     */
    public function addMois(\SMB\LoyerBundle\Entity\Mois $mois)
    {
        $this->mois[] = $mois;

        return $this;
    }

    /**
     * Remove mois
     *
     * @param \SMB\LoyerBundle\Entity\Mois $mois
     */
    public function removeMois(\SMB\LoyerBundle\Entity\Mois $mois)
    {
        $this->mois->removeElement($mois);
    }

    /**
     * Get mois
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMois()
    {
        return $this->mois;
    }


    /**
     * Set codification
     *
     * @param \SMB\LoyerBundle\Entity\Codification $codification
     * @return Paiement
     */
    public function setCodification(\SMB\LoyerBundle\Entity\Codification $codification)
    {
        $this->codification = $codification;

        $codification->addPaiement($this);

        return $this;
    }

    /**
     * Get codification
     *
     * @return \SMB\LoyerBundle\Entity\Codification 
     */
    public function getCodification()
    {
        return $this->codification;
    }
}

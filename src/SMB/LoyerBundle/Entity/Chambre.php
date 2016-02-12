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
     * Constructor
     */
    public function __construct()
    {
        $this->codifications = new \Doctrine\Common\Collections\ArrayCollection();
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
}

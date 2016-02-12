<?php

namespace SMB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Droit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\UserBundle\Entity\DroitRepository")
 */
class TypeAutorisation
{
    /**
     * @var autorisations
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\Autorisation", mappedBy="typeAutorisation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $autorisations;

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
     * @return Droit
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
     * Constructor
     */
    public function __construct()
    {
        $this->autorisations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add autorisations
     *
     * @param \SMB\UserBundle\Entity\Autorisation $autorisations
     * @return Droit
     */
    public function addAutorisation(\SMB\UserBundle\Entity\Autorisation $autorisations)
    {
        $this->autorisations[] = $autorisations;

        return $this;
    }

    /**
     * Remove autorisations
     *
     * @param \SMB\UserBundle\Entity\Autorisation $autorisations
     */
    public function removeAutorisation(\SMB\UserBundle\Entity\Autorisation $autorisations)
    {
        $this->autorisations->removeElement($autorisations);
    }

    /**
     * Get autorisations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAutorisations()
    {
        return $this->autorisations;
    }
}

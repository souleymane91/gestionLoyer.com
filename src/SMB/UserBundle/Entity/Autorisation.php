<?php

namespace SMB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Autorisation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Autorisation
{
    /**
     * @var profils
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\Profil", mappedBy="autorisations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $profils;

    /**
     * @var typeAutorisation
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\TypeAutorisation", inversedBy="autorisations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeAutorisation;

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
     * @return Autorisation
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
        $this->profils = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profils
     *
     * @param \SMB\UserBundle\Entity\Profil $profils
     * @return Autorisation
     */
    public function addProfil(\SMB\UserBundle\Entity\Profil $profils)
    {
        $this->profils[] = $profils;

        return $this;
    }

    /**
     * Remove profils
     *
     * @param \SMB\UserBundle\Entity\Profil $profils
     */
    public function removeProfil(\SMB\UserBundle\Entity\Profil $profils)
    {
        $this->profils->removeElement($profils);
    }

    /**
     * Get profils
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfils()
    {
        return $this->profils;
    }

    /**
     * Add typeAutorisation
     *
     * @param \SMB\UserBundle\Entity\TypeAutorisation $typeAutorisation
     * @return Autorisation
     */
    public function addTypeAutorisation(\SMB\UserBundle\Entity\TypeAutorisation $typeAutorisation)
    {
        $this->typeAutorisation[] = $typeAutorisation;

        return $this;
    }

    /**
     * Remove typeAutorisation
     *
     * @param \SMB\UserBundle\Entity\TypeAutorisation $typeAutorisation
     */
    public function removeTypeAutorisation(\SMB\UserBundle\Entity\TypeAutorisation $typeAutorisation)
    {
        $this->typeAutorisation->removeElement($typeAutorisation);
    }

    /**
     * Get typeAutorisation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeAutorisation()
    {
        return $this->typeAutorisation;
    }
}

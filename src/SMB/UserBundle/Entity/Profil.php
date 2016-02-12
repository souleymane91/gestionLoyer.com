<?php

namespace SMB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profil
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\UserBundle\Entity\ProfilRepository")
 */
class Profil
{
    /**
     * @var user
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\User", mappedBy="profils")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateurs;

    /**
     * @var autorisations
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\Autorisation", inversedBy="profils", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
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
     * @return Profil
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
     * @return Profil
     */
    public function addAutorisation(\SMB\UserBundle\Entity\Autorisation $autorisations)
    {
        $this->autorisations[] = $autorisations;

        $autorisations->addProfil($this);

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

    /**
     * Add utilisateurs
     *
     * @param \SMB\UserBundle\Entity\SMBUserBundle:User $utilisateurs
     * @return Profil
     */
    public function addUtilisateur(\SMB\UserBundle\Entity\User $utilisateurs)
    {
        $this->utilisateurs[] = $utilisateurs;

        return $this;
    }

    /**
     * Remove utilisateurs
     *
     * @param \SMB\UserBundle\Entity\SMBUserBundle:User $utilisateurs
     */
    public function removeUtilisateur(\SMB\UserBundle\Entity\User $utilisateurs)
    {
        $this->utilisateurs->removeElement($utilisateurs);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }
}

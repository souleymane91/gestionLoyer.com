<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Etudiant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\LoyerBundle\Entity\EtudiantRepository")
 */
class Etudiant
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
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre prénom.")
     * @Assert\Length(
     *      min=2, 
     *      max=30, 
     *      minMessage="Le prénom doit comporter plus de {{ limit }} caractères"),
     *      maxMessage="Le prénom ne doit pas comporter plus de {{ limit }} caractères"
     *  )
     * @Assert\Regex(
     *      pattern="/\d/",
     *      match=false,
     *      message="Le prénom ne peut pas contenir des chiffres"
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre nom.")
     * @Assert\Length(
     *      min=2, 
     *      max=30, 
     *      minMessage="Le nom doit comporter plus de {{ limit }} caractères"),
     *      maxMessage="Le nom ne doit pas comporter plus de {{ limit }} caractères"
     *  )
     * @Assert\Regex(
     *      pattern="/\d/",
     *      match=false,
     *      message="Le nom ne peut pas contenir des chiffres"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une adresse mail.")
     * @Assert\Email(message="Cet adresse email n'est pas valide.")
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="telephone", type="integer")
     * @Assert\Regex(
     *      pattern = "/^\d{9}$/",
     *      match = true,
     *      message = "Le numéro saisi n'est pas valide."
     * )
     */
    private $telephone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="caution", type="boolean")
     */
    private $caution;


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
     * Set prenom
     *
     * @param string $prenom
     * @return boolean
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
            
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Etudiant
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Etudiant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     * @return Etudiant
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return integer 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set caution
     *
     * @param boolean $caution
     * @return Etudiant
     */
    public function setCaution($caution)
    {
        $this->caution = $caution;

        return $this;
    }

    /**
     * Get caution
     *
     * @return boolean 
     */
    public function getCaution()
    {
        return $this->caution;
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
     * @return Etudiant
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

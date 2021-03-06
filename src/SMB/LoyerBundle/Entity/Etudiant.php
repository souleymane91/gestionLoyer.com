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
     *      pattern="/^[^0-9]{2}/",
     *      match=true,
     *      message="Veuillez saisir un prénom valide"
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
     *      pattern="/^[^0-9]{2}[\w\W]{1,}$/",
     *      match=true,
     *      message="Veuillez saisir un nom valide"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique = true)
     * @Assert\NotBlank(message="Veuillez saisir une adresse mail.")
     * @Assert\Email(message="Cet adresse email n'est pas valide.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string")
     * @Assert\Regex(
     *      pattern = "/^77|70|78|76[0-9]{3}[0-9]{2}[0-9]{2}$/",
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
     * Set supprime
     *
     * @param boolean $supprime
     * @return Etudiant
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
        $this->caution = TRUE;
        $this->supprime = false;//à la création l'étudiant n'est pas supprimé
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

    /**
     * Set telephone
     *
     * @param string $telephone
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
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
    
    /******************************************************
     * fonction qui recupère la liste de tous les étudiants
     ******************************************************/
    public static function listEtudiants(\SMB\LoyerBundle\Controller\EtudiantController $cont){
        //on recupère la liste de tous les étudiants
        //qui n'ont pas été supprimés
        $listEtudiants=$cont->getDoctrine()
                            ->getManager()
                            ->getRepository("SMBLoyerBundle:Etudiant")
                            ->listEtudiants();
        return $listEtudiants;
    }
    
    /***************************************************
     * fonction qui per permet de recupérer un etudiant
     * à partir d'un id
     ***************************************************/
    public static function getEtudiant($em, $id){
        $etudiant = $em->getRepository("SMBLoyerBundle:Etudiant")
                       ->find($id);
        return $etudiant;
    }
    
    /******************************************************
     * foncton qui permet de recupérer la codification d'un
     * etudiant pour un registre donné
     ******************************************************/
    public function codification($em, $registre){
        $codification = $em->getRepository("SMBLoyerBundle:Codification")
                           ->codification($this,$registre);
        return $codification;
    }
    
    /*****************************************************
     * fonction qui vérifie si un mail existe ou pas
     * renvoie true si le mail existe déja et false sinon
     *****************************************************/
    public function mailExiste(\SMB\LoyerBundle\Controller\EtudiantController $cont){
        $existe = $cont->getDoctrine()
                       ->getManager()
                       ->getRepository("SMBLoyerBundle:Etudiant")
                       ->mailExiste($this->email);
        return($existe);
    } 
}

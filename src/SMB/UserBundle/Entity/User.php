<?php

namespace SMB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SMB\UserBundle\Entity\UserRepository")
 * @UniqueEntity(fields="username", message="Il existe déja un utilisateur avec ce nom d'utilisateur.")
 * @UniqueEntity(fields="email", message="Il existe déja un utilisateur avec cet adresse email.")
 */
class User implements UserInterface{

    /**
     * @var profils
     *
     * @ORM\ManyToMany(targetEntity="SMB\UserBundle\Entity\Profil", inversedBy="utilisateurs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $profils;

    

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
     * @Assert\NotBlank(message="Veuillez renseigner ce champ.")
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner ce champ.")
     * @Assert\Email(message="Cet adresse email n'est pas valide.")
     */
    private $email;



    /**
     * @var string
     * 
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner ce champ.")
     */
    private $username;



    /**
     * @var array
     * 
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;



    /**
     * @var string
     * 
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner ce champ.")
     * @Assert\Length(
     *      min=4,
     *      minMessage="Le mot de passe doit contenir au moins {{ limit }} caractères"
     *  )
     */
    private $password;



    /**
     * @var string
     * 
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="supprime", type="boolean")
     */
    private $supprime;



    public function __construct(){
        $this->salt=base_convert(sha1(uniqid(mt_rand(),true)), 16, 36);
        $this->supprime = false;
    }

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
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
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
     * @return User
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
     * @return User
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


    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }
    
    public function getSupprime(){
        return $this->supprime;
    }

    public function eraseCredentials() {
        // Ici nous n'avons rien à effacer. 
        // Cela aurait été le cas si nous avions un mot de passe en clair.
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
    
    /**
     * Set supprime
     *
     * @param boolean $supprime
     * @return User
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Add profils
     *
     * @param \SMB\UserBundle\Entity\SMBUserBundle:Profil $profils
     * @return User
     */

    public function addProfil(\SMB\UserBundle\Entity\Profil $profils)
    {
        $this->profils[] = $profils;
        //on met le role sous son format
        $role = "ROLE_".$profils->getLibelle();
        //on ajoute le profil comme role
        $this->setRoles(array($role));
        //on lie le profil à l'utilisateur
        $profils->addUtilisateur($this);

        return $this;
    }

    /**
     * Remove profils
     *
     * @param \SMB\UserBundle\Entity\SMBUserBundle:Profil $profils
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
    
    
    /******************************************************
     * fonction qui recupère l'ensemble des utilisateurs
     ******************************************************/
    public static function listUtilisateurs(\SMB\UserBundle\Controller\UserController $cont){
        //on recupère la liste de tous les utilisateurs
        //qui n'ont pas été supprimés
        $listUtilisateurs=$cont->getDoctrine()
                               ->getManager()
                               ->getRepository("SMBUserBundle:User")
                               ->listUtilisateurs();
        return $listUtilisateurs;
    }
}

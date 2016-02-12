<?php

namespace SMB\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', 'text')
            ->add('nom', 'text')
            ->add('email', 'text')
            ->add('username', 'text')
            ->add('password','password')
            ->add('profils', 'entity', array(
                'class' => "SMBUserBundle:Profil",
                'property' => 'libelle',
                'multiple' => false,
                'expanded' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SMB\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_userbundle_user';
    }
}

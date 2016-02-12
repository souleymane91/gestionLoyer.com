<?php

namespace SMB\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfilType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', 'text')
            ->add('autorisations', 'entity', array(
                'class'    => "SMBUserBundle:Autorisation",
                'property' => "libelle",
                'multiple' => true,
                "expanded" => true
                ))
            ->add('ajouter', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SMB\UserBundle\Entity\Profil'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_userbundle_profil';
    }
}

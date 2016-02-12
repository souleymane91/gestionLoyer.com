<?php

namespace SMB\LoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CodificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('chambre','entity', array(
                'class'=>'SMBLoyerBundle:Chambre',
                'property'=>'numero',
                'multiple'=>false
            ))
            ->add('pavion','entity', array(
                'class'=>'SMBLoyerBundle:Pavion',
                'property'=>'libelle',
                'multiple'=>false
            ))
            ->add('enregistrer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SMB\LoyerBundle\Entity\Codification'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_loyerbundle_codification';
    }
}

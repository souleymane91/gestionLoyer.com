<?php

namespace SMB\LoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaiementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mois','entity', array(
                'class'=>'SMBLoyerBundle:Mois',
                'property'=>'libelle',
                'multiple'=>true,
                'expanded'=>true
            ))
            ->add('valider','submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SMB\LoyerBundle\Entity\Paiement'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_loyerbundle_paiement';
    }
}

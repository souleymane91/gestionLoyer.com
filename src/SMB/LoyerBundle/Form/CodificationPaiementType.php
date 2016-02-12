<?php

namespace SMB\LoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CodificationPaiementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_loyerbundle_codification_paiement';
    }

    public function getParent()
    {
        return new AdvertType();
    }
}

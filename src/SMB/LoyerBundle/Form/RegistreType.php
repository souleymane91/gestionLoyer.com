<?php

namespace SMB\LoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistreType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anneeScolaire', 'text')
            ->add('moisDebut', 'text')
            ->add('moisFin', 'text')
            ->add('mensualite', 'number')
            ->add('caution', 'number')
            ->add('enregistrer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SMB\LoyerBundle\Entity\Registre'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smb_loyerbundle_registre';
    }
}

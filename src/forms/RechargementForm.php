<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RechargementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePlace', 'integer', array('label' => 'Nombre de places', 'required' => true))
            ->add('prixUnitaire', 'money', array('label' => 'Prix unitaire', 'precision' => 2, 'required' => true))
            ->add('placesUtilise', 'integer', array('label' => 'Places utilisées', 'required' => true))
        ;
    }

    public function getName()
    {
        return 'rechargement_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'model\Entite\Rechargement'
        ));
    }

    public function getExtendedType()
    {
        return 'rechargement';
    }
}



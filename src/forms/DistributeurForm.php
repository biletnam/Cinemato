<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DistributeurForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('label' => 'Nom', 'required' => true))
            ->add('prenom', 'text', array('label' => 'Prénom', 'required' => false))
            ->add('adresse', 'textarea', array('label' => 'Adresse', 'required' => false))
            ->add('telephone', 'text', array('label' => 'N° de téléphone', 'required' => false))
        ;
    }

    public function getName()
    {
        return 'distributeur_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'distributeur';
    }
}

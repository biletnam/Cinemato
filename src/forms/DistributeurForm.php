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
            ->add('prenom', 'text', array('label' => 'Prénom', 'required' => true))
            ->add('adresse', 'textarea', array('label' => 'Adresse', 'required' => false))
            ->add('tel', 'text', array('label' => 'N° de téléphone', 'required' => false))
        ;
    }

    public function getName()
    {
        return 'distributeur_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'model\Entite\Distributeur',
        );
    }

    public function getExtendedType()
    {
        return 'distributeur';
    }
}

<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonneAbonneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('label' => 'Nom', 'required' => true))
            ->add('prenom', 'text', array('label' => 'Prénom', 'required' => true))
            ->add('placeRestante', 'integer', array('label' => 'Places restantes', 'required' => true))
        ;
    }

    public function getName()
    {
        return 'personne_abonne_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'model\Entite\PersonneAbonne'
        ));
    }

    public function getExtendedType()
    {
        return 'personne_abonne';
    }
}

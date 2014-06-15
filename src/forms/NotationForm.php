<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', array('label' => 'Numéro de ticket', 'required' => true))
            ->add('note', 'text', array('label' => 'Votre note', 'required' => true))
        ;
    }

    public function getName()
    {
        return 'notation_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'notation';
    }
}

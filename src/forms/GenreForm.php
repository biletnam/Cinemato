<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('label' => 'Nom', 'required' => true))
            // ->add('email', 'text', array('label' => 'Adresse e-mail', 'required' => true))
            // ->add('phone', 'text', array('label' => 'N° de téléphone', 'required' => false))
            // ->add('address', 'textarea', array('label' => 'Adresse', 'required' => false))
            // ->add('starttime', 'datetime', array('label' => 'Du', 'required' => true, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy hh:mm', 'attr' => array('data-format' => 'dd-MM-yyyy hh:mm')))
            // ->add('endtime', 'datetime', array('label' => 'Jusqu\'au', 'required' => true, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy hh:mm', 'attr' => array('data-format' => 'dd-MM-yyyy hh:mm')))
        ;
    }

    public function getName()
    {
        return 'genre_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'model\Entite\Genre',
        );
    }

    public function getExtendedType()
    {
        return 'genre';
    }
}

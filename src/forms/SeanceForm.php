<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeanceForm extends AbstractType
{
    protected $salles;
    protected $films;

    public function __construct($salles, $films) {
        $this->salles = $salles;
        $this->films = $films;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $salles = array();
        foreach ($this->salles as $salle) {
            $salles[$salle->getNom()] = $salle->getNom();
        }
        $films = array();
        foreach ($this->films as $film) {
            $films[$film->getId()] = $film->toString();
        }

        $builder
            ->add('dateSeance', 'datetime', array('label' => 'Horaires', 'required' => true, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy HH:mm:ss', 'attr' => array('data-format' => 'DD-MM-YYYY HH:mm:ss')))
            ->add('film', 'choice', array('label' => 'Film', 'required' => true, 'choices' => $films))
            ->add('salle', 'choice', array('label' => 'Salle', 'required' => true, 'choices' => $salles))
            ->add('doublage', 'text', array('label' => 'Doublage', 'required' => true))
        ;
    }

    public function getName()
    {
        return 'seance_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'seance';
    }
}

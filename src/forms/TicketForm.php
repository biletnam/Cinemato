<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketForm extends AbstractType
{
    protected $seances;
    protected $abonnes;
    protected $tarifs;

    public function __construct($seances, $abonnes, $tarifs)
    {
        $this->seances = $seances;
        $this->abonnes = $abonnes;
        $this->tarifs = $tarifs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $seances = array();
        foreach ($this->seances as $seance) {
            $seances[$seance->getId()] = $seance->toString();
        }

        $abonnes = array();
        foreach ($this->abonnes as $abonne) {
            $abonnes[$abonne->getId()] = $abonne->toString();
        }

        $tarifs = array();
        foreach ($this->tarifs as $tarif) {
            $tarifs[$tarif->getNom()] = $tarif->toString();
        }

        $builder
            ->add('hasClientAccount', 'checkbox', array('label' => 'Le client possède un compte abonné', 'required' => false, 'data' => true))
            ->add('seance', 'choice', array('label' => 'Séance', 'required' => true, 'choices' => $seances, 'empty_value' => 'Choisissez la séance'))
            ->add('abonne', 'choice', array('label' => 'Abonné', 'required' => false, 'choices' => $abonnes, 'empty_value' => 'Choisissez le compte abonné'))
            ->add('tarif', 'choice', array('label' => 'Tarif', 'required' => false, 'choices' => $tarifs, 'empty_value' => 'Choisissez le tarif'))
        ;
    }

    public function getName()
    {
        return 'ticket_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'ticket';
    }
}



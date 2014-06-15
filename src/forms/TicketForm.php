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
            $seances[$seance->getTimestamp()] = $seance->toString();
        }

        $abonnes = array();
        foreach ($this->abonnes as $abonne) {
            $abonnes[$abonnne->getId()] = $abonne->toString();
        }

        $tarifs = array();
        foreach ($this->tarifs as $tarif) {
            $tarifs[$tarif->getNom()] = $tarif->toString();
        }

        $builder
            ->add('seance', 'choice', array('label' => 'Séance', 'required' => true, 'choices' => $seances))
            ->add('abonne', 'choice', array('label' => 'Abonné', 'required' => true, 'choices' => $abonnes))
            ->add('tarif', 'choice', array('label' => 'Tarif', 'required' => true, 'choices' => $tarifs))
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'model\Entite\Ticket'
        ));
    }

    public function getExtendedType()
    {
        return 'ticket';
    }
}



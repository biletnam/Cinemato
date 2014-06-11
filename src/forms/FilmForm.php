<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilmForm extends AbstractType
{
    protected $genres;
    protected $distributeurs;

    public function __construct($genres, $distributeurs) {
        $this->genres = $genres;
        $this->distributeurs = $distributeurs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $genres = array();
        foreach ($this->genres as $genre) {
            $genres[$genre->getNom()] = $genre->getNom();
        }
        $distributeurs = array();
        foreach ($this->distributeurs as $distributeur) {
            $distributeurs[$distributeur->getId()] = $distributeur->toString();
        }

        $builder
            ->add('titre', 'text', array('label' => 'Titre', 'required' => true))
            ->add('dateDeSortie', 'date', array('label' => 'Date de sortie', 'required' => true, 'input' => 'datetime', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array('data-format' => 'DD-MM-YYYY')))
            ->add('ageMinimum', 'integer', array('label' => 'Age requis', 'required' => true))
            ->add('genre', 'choice', array('label' => 'Genre', 'required' => true, 'choices' => $genres))
            ->add('distributeur', 'choice', array('label' => 'Distributeur', 'required' => true, 'choices' => $distributeurs))
        ;
    }

    public function getName()
    {
        return 'film_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'film';
    }
}

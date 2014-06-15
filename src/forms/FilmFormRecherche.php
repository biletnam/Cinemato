<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilmFormRecherche extends AbstractType
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
        ;
    }

    public function getName()
    {
        return 'film_form_recherche';
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

<?php

namespace model\Entite;

/**
 * Entité Salle
 */
class Salle
{
    private $nom;

    private $nombreDePlaces;

    public function __construct($nom='', $nombreDePlaces=null) {
        $this->nom = $nom;
        $this->nombreDePlaces = $nombreDePlaces;

        return $this;
    }

    public function toString()
    {
        return $this->getNom();
    }

    public function __toString()
    {
        return $this->getNom();
    }

    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNbPlaces($nombreDePlaces) {
        $this->nombreDePlaces = $nombreDePlaces;

        return $this;
    }

    public function getNbPlaces() {
        return $this->nombreDePlaces;
    }
}

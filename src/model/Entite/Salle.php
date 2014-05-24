<?php

namespace model\Entite;

/**
 * Entité Salle
 */
class Salle
{
    private $name;

    private $nombreDePlaces;

    public function __construct(string $name, string $nombreDePlaces) {
        $this->name = $name;
        $this->nombreDePlaces = $nombreDePlaces;

        return $this;
    }

    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setNombreDePlaces(string $nombreDePlaces) {
        $this->nombreDePlaces = $nombreDePlaces;

        return $this;
    }

    public function getNombreDePlaces() {
        return $this->nombreDePlaces;
    }
}

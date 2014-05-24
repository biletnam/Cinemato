<?php

namespace model\Entite;

class Genre
{
    private $nom;

    public function __construct($nom = '') {
        $this->setNom($nom);

        return $this;
    }

    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    public function getNom() {
        return $this->nom;
    }

    /**
     * Magic method : string representation
     *
     * @example echo $genre; "{nom}"
     *
     * @return string
     */
    public function __toString() {
        return $this->nom;
    }

    /**
     * Instance helper : string representation
     *
     * @example echo $genre->toString(); "{nom}"
     *
     * @return string
     */
    public function toString() {
        return $this->nom;
    }
}

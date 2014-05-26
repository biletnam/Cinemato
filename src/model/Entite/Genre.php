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

    /**
     * Instantiate a new Genre from database values array
     *
     * @param array $data
     * @return Genre
     */
    public static function mapFromData($data) {
        $instance = new self($data['pk_nom_genre']);

        return $instance;
    }
}

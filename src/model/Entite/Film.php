<?php

namespace model\Entite;

use \DateTime;

class Film
{
    private $id;

    private $titre;

    private $dateDeSortie;

    private $ageMinimum;

    private $genre;

    private $distributeur;

    public function __construct($titre = '', $dateDeSortie = null, $ageMinimum = 0, $genre = null, $distributeur = null) {
        $this->setTitre($titre);
        $this->setDateDeSortie($dateDeSortie);
        $this->setAgeMinimum($ageMinimum);
        $this->setGenre($genre);
        $this->setDistributeur($distributeur);

        return $this;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setDateDeSortie($dateDeSortie) {
        $this->dateDeSortie = $dateDeSortie;

        return $this;
    }

    public function getDateDeSortie() {
        return $this->dateDeSortie;
    }

    public function setAgeMinimum($ageMinimum) {
        $this->ageMinimum = $ageMinimum;

        return $this;
    }

    public function getAgeMinimum() {
        return $this->ageMinimum;
    }

    public function setGenre($genre) {
        $this->genre = $genre;

        return $this;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setDistributeur($distributeur) {
        $this->distributeur = $distributeur;

        return $this;
    }

    public function getDistributeur() {
        return $this->distributeur;
    }

    /**
     * Magic method : string representation
     *
     * @example echo $genre; "{titre}"
     *
     * @return string
     */
    public function __toString() {
        return $this->titre;
    }

    /**
     * Instance helper : string representation
     *
     * @example echo $genre->toString(); "{titre}"
     *
     * @return string
     */
    public function toString() {
        return $this->titre;
    }

    /**
     * Instantiate a new Film from database values array
     *
     * @param array $data
     * @return Film
     */
    public static function mapFromData($data) {
        $instance = new self($data['titre'], $data['date_sortie'], $data['age_min'], $data['genre'], $data['distributeur']);

        return $instance;
    }
}

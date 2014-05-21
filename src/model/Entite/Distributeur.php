<?php

namespace model\Entite;

class Distributeur
{
    private $id;

    private $nom;

    private prenom;

    private adresse;

    private telephone;

    public function __construct($nom = '', $prenom = '', $adresse = '', $telephone = '') {
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setAdresse($adresse);
        $this->setTelephone($telephone);

        return $this;
    }

    public function setId(int $id) {
        $this->id = $id;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setNom(string $nom) {
        $this->nom = $nom;

        return $this;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setPrenom(string $prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setAdresse(string $adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function setTelephone(string $telephone) {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * Magic method : string representation
     *
     * @example echo $distributeur; "{prenom} {nom}"
     *
     * @return string
     */
    public function __toString() {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Instance helper : string representation
     *
     * @example echo $distributeur->toString(); "{prenom} {nom}"
     *
     * @return string
     */
    public function toString() {
        return $this->prenom . ' ' . $this->nom;
    }
}

<?php

namespace model\Entite;

/**
 * Entité Produit
 */
abstract class Produit
{
    private $codeBarre;

    private $nomDeProduit;

    private $prix;

    public function __construct($codeBarre='', $nomDeProduit='', $prix=0.0) {
        $this->setCodeBarre($codeBarre);
        $this->setNomDeProduit($nomDeProduit);
        $this->setPrix($prix);

        return $this;
    }

    public function setCodeBarre($codeBarre) {
        $this->codeBarre = intval($codeBarre);

        return $this;
    }

    public function getCodeBarre() {
        return $this->codeBarre;
    }

    public function setNomDeProduit($nomDeProduit) {
        $this->nomDeProduit = $nomDeProduit;

        return $this;
    }

    public function getNomDeProduit() {
        return $this->nomDeProduit;
    }

    public function setNom($nomDeProduit) {
        return $this->setNomDeProduit($nomDeProduit);
    }

    public function getNom() {
        return $this->getNomDeProduit();
    }

    public function setPrix($prix) {
        $this->prix = floatval($prix);

        return $this;
    }

    public function getPrix() {
        return $this->prix;
    }
}

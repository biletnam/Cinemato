<?php

namespace model\Entite;

/**
 * Entité Produit
 */
class Produit
{
    private $codeBarre;

    private $nomDeProduit;

    private $prix;

    public function __construct($codeBarre='', $nomDeProduit='', $prix=0) {
        $this->codeBarre = $codeBarre;
        $this->nomDeProduit = $nomDeProduit;
        $this->prix = $prix;

        return $this;
    }

    public function setCodeBarre($codeBarre) {
        $this->codeBarre = $codeBarre;

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

    public function setPrix($prix) {
        $this->prix = $prix;

        return $this;
    }

    public function getPrix() {
        return $this->prix;
    }
}

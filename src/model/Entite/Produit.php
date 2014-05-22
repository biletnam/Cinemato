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

    public function __construct(string $codeBarre, string $nomDeProduit, string $prix) {
        $this->codeBarre = $codeBarre;
        $this->nomDeProduit = $nomDeProduit;
        $this->prix = $prix;

        return $this;
    }

    public function setCodeBarre(string $codeBarre) {
        $this->codeBarre = $codeBarre;

        return $this;
    }

    public function getCodeBarre() {
        return $this->codeBarre;
    }

    public function setNomDeProduit(string $nomDeProduit) {
        $this->nomDeProduit = $nomDeProduit;

        return $this;
    }

    public function getNomDeProduit() {
        return $this->nomDeProduit;
    }

    public function setPrix(string $prix) {
        $this->prix = $prix;

        return $this;
    }

    public function getPrix() {
        return $this->prix;
    }
}

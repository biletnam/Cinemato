<?php

namespace model\Entite;

/**
 * Entité Ticket
 */
class Tarif
{
	private $nom;

	private $tarif;

    public function __construct($nomTarif, $tarif) {
        $this->nom = $nomTarif;
        $this->tarif = $tarif;

        return $this;
    }

    public function setNom($nomTarif) {
        $this->nom = $nomTarif;

        return $this;
    }

    public function getNom() {
        return $this->nom;
    }


    public function setTarif(float $tarif) {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarif() {
        return $this->tarif;
    }

}

?>
<?php

namespace model\Entite;

/**
 * Entité Ticket
 */
class Tarif
{
	private $nom;

	private $tarif;

    public function __toString()
    {
        return $this->nom . ' : ' . $this->tarif . '€';
    }

    public function toString()
    {
        return $this->nom . ' : ' . $this->tarif . '€';
    }

    public function __construct($nomTarif = '', $tarif = null) {
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


    public function setTarif($tarif) {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarif() {
        return $this->tarif;
    }

}

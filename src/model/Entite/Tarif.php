<?php

namespace model\Entite;

/**
 * Entité Ticket
 */
class Tarif
{
	private $nomTarif;

	private $tarif;

    public function __construct($nomTarif, $tarif) {
        $this->nomTarif = $nomTarif;
        $this->tarif = $tarif;

        return $this;
    }

    public function setNomTarif($nomTarif) {
        $this->nomTarif = $nomTarif;

        return $this;
    }

    public function getNomTarif() {
        return $this->nomTarif;
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
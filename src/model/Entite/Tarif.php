<?php

namespace model\Entite;

/**
 * Entité Ticket
 */
class Tarif
{
	private $nom;

	private $tarif;

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

?>
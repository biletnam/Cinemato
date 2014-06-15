<?php

namespace model\Entite;

/**
 * Entité Ticket
 */
class Tarif
{
	private $nom;

	private $tarif;

<<<<<<< HEAD
    public function __construct($nomTarif = '', $tarif = null) {
        $this->nom = $nomTarif;
        $this->tarif = $tarif;

        return $this;
    }

=======
>>>>>>> b813f82f392d7a89c9058cc641ea85856d4e16a0
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

<?php

namespace model\Entite;

use \DateTime;

/**
 * Entité Seance
 */
class Seance
{

    private $dateSeance;

    private $salle;

    private $film;

    private $doublage;

    public function toString()
    {
        return $this->getFilm()->getTitre() . ' le ' . $this->getDateSeance()->format('d-m-Y H:i');
    }

    public function setDateSeance($dateSeance) {
        $this->dateSeance = $dateSeance;

        return $this;
    }

    public function getTimestamp()
    {
        return $this->getDateSeance()->getTimestamp();
    }

    public function getDateSeance() {
        return $this->dateSeance;
    }

    public function setSalle($salle) {
        $this->salle = $salle;

        return $this;
    }

    public function getSalle() {
        return $this->salle;
    }

    public function setFilm($film) {
        $this->film = $film;

        return $this;
    }

    public function getFilm() {
        return $this->film;
    }

    public function setDoublage($doublage) {
        $this->doublage = $doublage;

        return $this;
    }

    public function getDoublage() {
        return $this->doublage;
    }


}

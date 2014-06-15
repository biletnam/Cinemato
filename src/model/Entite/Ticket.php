<?php

namespace model\Entite;

use \DateTime;

/**
 * Entité Ticket
 */
class Ticket
{
    private $id;

    private $dateDeVente;

    private $note;

    private $seance;

    private $abonne;

    private $vendeur;

    private $tarif;

    public function __construct($dateDeVente = null, $note=null, $seance=null, $abonne=null, $vendeur=null, $tarif=null) {
        if (is_null($dateDeVente)) {
            $dateDeVente = new DateTime();
        }
        $this->dateDeVente = $dateDeVente;
        $this->note = $note;
        $this->seance = $seance;
        $this->abonne = $abonne;
        $this->vendeur = $vendeur;
        $this->tarif = $tarif;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
    	$this->id = $id;
    	return $this;
    }

    public function setDateDeVente($dateDeVente) {
        $this->dateDeVente = $dateDeVente;

        return $this;
    }

    public function getDateDeVente() {
        return $this->dateDeVente;
    }

    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    public function getNote() {
        return $this->note;
    }

    public function setSeance($seance) {
        $this->seance = $seance;

        return $this;
    }

    public function getSeance() {
        return $this->seance;
    }

    public function setAbonne($abonne) {
        $this->abonne = $abonne;

        return $this;
    }

    public function getAbonne() {
        return $this->abonne;
    }

    public function setVendeur($vendeur) {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getVendeur() {
        return $this->vendeur;
    }

    public function setTarif($tarif) {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarif() {
        return $this->tarif;
    }
}

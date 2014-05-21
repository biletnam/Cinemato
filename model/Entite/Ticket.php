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

    private $sceance;

    private $abonne;

    private $vendeur;

    private $tarif;

    public function __construct(DateTime $dateDeVente, int $note) {
        $this->dateDeVente = $dateDeVente;
        $this->note = $note;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setDateDeVente(DateTime $dateDeVente) {
        $this->dateDeVente = $dateDeVente;

        return $this;
    }

    public function getDateDeVente() {
        return $this->dateDeVente;
    }

    public function setNote(int $note) {
        $this->note = $note;

        return $this;
    }

    public function getNote() {
        return $this->note;
    }

    public function setSceance(Sceance $sceance) {
        $this->sceance = $sceance;

        return $this;
    }

    public function getSceance() {
        return $this->sceance;
    }

    public function setAbonne(Abonne $abonne) {
        $this->abonne = $abonne;

        return $this;
    }

    public function getAbonne() {
        return $this->abonne;
    }

    public function setVendeur(Vendeur $vendeur) {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getVendeur() {
        return $this->vendeur;
    }

    public function setTarif(Tarif $tarif) {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarif() {
        return $this->tarif;
    }
}

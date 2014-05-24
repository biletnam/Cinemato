<?php

namespace model\Entite;

class Rechargement {
	private $id;
	private $nombrePlace;
	private $prixUnitaire;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getNombrePlace() {
		return $this->nombrePlace;
	}
	public function setNombrePlace($nombrePlace) {
		$this->nombrePlace = $nombrePlace;
		return $this;
	}
	public function getPrixUnitaire() {
		return $this->prixUnitaire;
	}
	public function setPrixUnitaire($prixUnitaire) {
		$this->prixUnitaire = $prixUnitaire;
		return $this;
	}
}
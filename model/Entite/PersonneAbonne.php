<?php

namespace model\Entite;

class PersonneAbonne extends Personne {
	private $placeRestante;
	private $recharges;
	public function __construct() {
	}
	public function __construct($nom, $prenom) {
		parent::setNom ( $nom );
		parent::setPrenom ( $prenom );
	}
	public function getPlaceRestante() {
		return $this->placeRestante;
	}
	public function setPlaceRestante($placeRestante) {
		$this->placeRestante = $placeRestante;
		return $this;
	}
	public function getRecharges() {
		return $this->recharges;
	}
	public function setRecharges($recharges) {
		$this->recharges = $recharges;
		return $this;
	}
}
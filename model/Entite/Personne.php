<?php

namespace model\Entite;

abstract class Personne {
	private $id;
	private $nom;
	private $prenom;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->$id = $id;
		return $this;
	}
	public function setNom(string $nom) {
		$this->nom = $nom;
		
		return $this;
	}
	public function getNom() {
		return $this->nom;
	}
	public function setPrenom(string $prenom) {
		$this->prenom = $prenom;
		
		return $this;
	}
	public function getPrenom() {
		return $this->prenom;
	}
}
<?php

namespace model\Entite;

class Personne {
	private $id;
	private $nom;
	private $prenom;

	public function __construct($nom = '', $prenom = '')
	{
		$this->setNom($nom);
		$this->setPrenom($prenom);

		return $this;
	}

	public function __toString()
	{
		return $this->nom . ' ' . $this->prenom;
	}

	public function toString()
	{
		return $this->nom . ' ' . $this->prenom;
	}

	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function setNom($nom) {
		$this->nom = $nom;

		return $this;
	}
	public function getNom() {
		return $this->nom;
	}
	public function setPrenom($prenom) {
		$this->prenom = $prenom;

		return $this;
	}
	public function getPrenom() {
		return $this->prenom;
	}
}

<?php

namespace model\Entite;

class PersonneVendeur extends Personne {
	public function __construct() {
	}
	public function __construct($nom, $prenom) {
		parent::setNom ( $nom );
		parent::setPrenom ( $prenom );
	}
}
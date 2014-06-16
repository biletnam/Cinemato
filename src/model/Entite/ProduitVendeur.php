<?php

namespace model\Entite;

use \DateTime;

class ProduitVendeur {
    private $id;
	private $vendeur;
	private $produit;
	private $date;

	public function __construct()
	{
		$this->date = new DateTime();

		return $this;
	}

	public function __toString()
	{
		return 'Vente du produit : ' . $this->produit->toString() . ' le ' . $this->date->format('d-m-Y');
	}

	public function toString()
	{
		return 'Vente du produit : ' . $this->produit->toString() . ' le ' . $this->date->format('d-m-Y');
	}

	public function getVendeur() {
		return $this->vendeur;
	}
	public function getProduit() {
		return $this->produit;
	}
	public function getDate() {
		return $this->date;
	}
	public function getId() {
	    return $this->id;
	}
	public function setVendeur($vendeur) {
		$this->vendeur = $vendeur;
		return $this;
	}
	public function setProduit($produit) {
		$this->produit = $produit;
		return $this;
	}
	public function setDate($date) {
		$this->date = $date;
		return $this;
	}
	public function setId($id) {
	    $this->id = $id;
	    return $this;
	}
}

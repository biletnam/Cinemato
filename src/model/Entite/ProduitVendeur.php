<?php

namespace model\Entite;

class ProduitVendeur {
	private $vendeur;
	private $produit;
	private $date;
	public function getVendeur() {
		return $this->vendeur;
	}
	public function getProduit() {
		return $this->produit;
	}
	public function getDate() {
		return $this->date;
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
		$this->date = date;
		return $this;
	}
}
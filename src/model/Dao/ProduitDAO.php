<?php

namespace model\Dao;

use \PDO;
use model\Entite\Produit;
use model\Entite\ProduitBoissons;
use model\Entite\ProduitAlimentaire;
use model\Entite\ProduitAutre;

class ProduitDAO {
	private $dao;
	public function __construct($dao) {
		$this->dao = $dao;
	}
	private function getDao() {
		return $this->dao;
	}
	public function find($id) {
		$produit = null;
		$query = 'SELECT *' . 'FROM tproduit p'.
		'LEFT JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit=pal.fkpk_code_barre_produit'.
		'LEFT JOIN tproduit_boisson pb ON p.pk_code_barre_produit=pb.fkpk_code_barre_produit'.
		'LEFT JOIN tproduit_autre pau ON p.pk_code_barre_produit=pau.fkpk_code_barre_produit'.
		'WHERE p.pk_code_barre_produit= :id';
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				
				if ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$produit = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produit;
	}
	public function findAll() {
		$produits = array ();
		$query = 'SELECT *' . 'FROM tproduit p'.
		'LEFT JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit=pal.fkpk_code_barre_produit'.
		'LEFT JOIN tproduit_boisson pb ON p.pk_code_barre_produit=pb.fkpk_code_barre_produit'.
		'LEFT JOIN tproduit_autre pau ON p.pk_code_barre_produit=pau.fkpk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
					array_push ( $produits, $produit );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produits;
	}

	public function findAllAlimentaire(){
		$produits = array ();
		$query = 'SELECT *' . 'FROM tproduit p'.
		'LEFT JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit=pal.fkpk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
					array_push ( $produits, $produit );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produits;
	}

	public function findAllBoisson(){
		$produits = array ();
		$query = 'SELECT *' . 'FROM tproduit p'.
		'LEFT JOIN tproduit_boisson pb ON p.pk_code_barre_produit=pb.fkpk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
					array_push ( $produits, $produit );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produits;
	}

	public function findAllAutres() {
		$produits = array ();
		$query = 'SELECT *' . 'FROM tproduit p'.
		'LEFT JOIN tproduit_autre pau ON p.pk_code_barre_produit=pau.fkpk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
					array_push ( $produits, $produit );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produits;
	}

	public function update($produit) {
		$query = 'UPDATE tproduit SET  nom_produit = :nom, prix = :prix WHERE pk_code_barre_produit = :id';
		$connection = $this->getDao ()->getConnexion ();
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $produit->getCodeBarre (),
						'nom' => $produit->getNom (),
						'prix' => $produit->getPrix()
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}

	public function delete($produit) {
		$query1 = 'DELETE FROM tproduit_alimentaire WHERE fkpk_code_barre_produit = :id';
		$query2 = 'DELETE FROM tproduit_boisson WHERE fkpk_code_barre_produit = :id';
		$query3 = 'DELETE FROM tproduit_alimentaire WHERE fkpk_code_barre_produit = :id';
		$query4 = 'DELETE FROM tproduit WHERE pk_code_barre_produit = :id';
		try {
			$connection = $this->getDao ()->getConnexion ();
			if ($produit instanceof ProduitAlimentaire) {
				if (! is_null ( $connection )) {
					$statement = $connection->prepare ( $query1 );
					$statement->execute ( array (
							'id' => $produit->getCodeBarre () 
					) );
					$statement = null;
					$connection = null;
				}
			} else if ($produit instanceof ProduitBoissons){
				$connection = $this->getDao ()->getConnexion ();
				if (! is_null ( $connection )) {
					$statement = $connection->prepare ( $query2 );
					$statement->execute ( array (
							'id' => $produit->getCodeBarre () 
					) );
					$statement = null;
					$connection = null;
				}
			}
			else{
				$connection = $this->getDao ()->getConnexion ();
				if (! is_null ( $connection )) {
					$statement = $connection->prepare ( $query3 );
					$statement->execute ( array (
							'id' => $produit->getCodeBarre () 
					) );
					$statement = null;
					$connection = null;
				}
			}
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				$statement = $connection->prepare ( $query4 );
				$statement->execute ( array (
						'id' => $produit->getCodeBarre () 
				) );
				$statement = null;
				$connection = null;
			}
		} catch ( \PDOException $e ) {
			throw $e;
		}
	}
	public static function bind($donnees) {
		if ($donnees ['pal.fkpk_code_barre_produit'] != null) {
			$produit = new ProduitAlimentaire ();
		} else if( $donnees ['pb.fkpk_code_barre_produit'] != null){
			$produit = new ProduitBoissons ();
		}
		else {
			$produit = new ProduitAutre ();
		}
		$produit->setCodeBarre($donnees['p.pk_code_barre_produit']);
		$produit->setNom($donnees['p.nom_produit']);
		$produit->setPrix($donnees['p.prix']);
		return $produit;
	}
}


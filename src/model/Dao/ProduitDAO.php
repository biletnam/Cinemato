<?php

namespace model\Dao;

use \PDO;
use model\Entite\Produit;
use model\Entite\ProduitBoisson;
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

	public function create($produit) {
		$success = false;

		$query1 = 'INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix) VALUES(:code_barre, :nom, :prix )';
		$query2 = 'INSERT INTO tproduit_alimentaire(pkfk_code_barre_produit) VALUES (:code_barre)';
		$query3 = 'INSERT INTO tproduit_boisson(pkfk_code_barre_produit) VALUES (:code_barre)';
		$query4 = 'INSERT INTO tproduit_autre(pkfk_code_barre_produit) VALUES (:code_barre)';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query1 );
				$success = $statement->execute ( array (
						'code_barre' => $produit->getCodeBarre (),
						'nom' => $produit->getNom(),
						'prix' => $produit->getPrix()
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		if($produit instanceof ProduitAlimentaire){
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				try {
					$statement = $connection->prepare ( $query2 );
					$success = $statement->execute ( array (
							'code_barre' => $produit->getCodeBarre ()
					) );
				} catch ( \PDOException $e ) {
					throw $e;
				}
			}
		}
		else if($produit instanceof ProduitBoisson){
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				try {
					$statement = $connection->prepare ( $query3 );
					$success = $statement->execute ( array (
							'code_barre' => $produit->getCodeBarre ()
					) );
				} catch ( \PDOException $e ) {
					throw $e;
				}
			}
		}
		else {
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				try {
					$statement = $connection->prepare ( $query4 );
					$success = $statement->execute ( array (
							'code_barre' => $produit->getCodeBarre ()
					) );
				} catch ( \PDOException $e ) {
					throw $e;
				}
			}
		}

		return $success;
	}

	public function find($id) {
		$produit = null;
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pal.pkfk_code_barre_produit as palcod,'.
                ' pb.pkfk_code_barre_produit as pbcod,'.
                ' pau.pkfk_code_barre_produit as paucod'.
                ' FROM tproduit p' .
                ' LEFT JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit=pal.pkfk_code_barre_produit' .
                ' LEFT JOIN tproduit_boisson pb ON p.pk_code_barre_produit=pb.pkfk_code_barre_produit' .
                ' LEFT JOIN tproduit_autre pau ON p.pk_code_barre_produit=pau.pkfk_code_barre_produit'.
                ' WHERE p.pk_code_barre_produit = :id';
		$connection = $this->getDao ()->getConnexion ();
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
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pal.pkfk_code_barre_produit as palcod,'.
                ' pb.pkfk_code_barre_produit as pbcod,'.
                ' pau.pkfk_code_barre_produit as paucod'.
                ' FROM tproduit p' .
                ' LEFT JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit=pal.pkfk_code_barre_produit' .
                ' LEFT JOIN tproduit_boisson pb ON p.pk_code_barre_produit=pb.pkfk_code_barre_produit' .
                ' LEFT JOIN tproduit_autre pau ON p.pk_code_barre_produit=pau.pkfk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ();
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

	public function findAllAlimentaires(){
		$produits = array ();
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pal.pkfk_code_barre_produit as palcod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit = pal.pkfk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ();
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

	public function findAlimentaire($codeBarre) {
		$produit = null;
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pal.pkfk_code_barre_produit as palcod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_alimentaire pal ON p.pk_code_barre_produit = pal.pkfk_code_barre_produit' .
                ' WHERE p.pk_code_barre_produit = :codeBarre';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute(array(
					'codeBarre' => $codeBarre
				));
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produit;
	}

	public function findAllBoissons(){
		$produits = array ();
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pb.pkfk_code_barre_produit as pbcod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_boisson pb ON p.pk_code_barre_produit = pb.pkfk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ();
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

	public function findBoisson($codeBarre) {
		$produit = null;
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pb.pkfk_code_barre_produit as pbcod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_boisson pb ON p.pk_code_barre_produit = pb.pkfk_code_barre_produit' .
                ' WHERE p.pk_code_barre_produit = :codeBarre';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute(array(
					'codeBarre' => $codeBarre
				));
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produit;
	}

	public function findAllAutres() {
		$produits = array ();
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pau.pkfk_code_barre_produit as paucod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_autre pau ON p.pk_code_barre_produit = pau.pkfk_code_barre_produit';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ();
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

	public function findAutre($codeBarre) {
		$produit = null;
		$query = 'SELECT p.pk_code_barre_produit AS codebarre,'.
                ' p.nom_produit AS nomproduit,'.
                ' p.prix as prix,'.
                ' pau.pkfk_code_barre_produit as paucod'.
                ' FROM tproduit p' .
                ' JOIN tproduit_autre pau ON p.pk_code_barre_produit = pau.pkfk_code_barre_produit' .
                ' WHERE p.pk_code_barre_produit = :codeBarre';
		$connection = $this->getDao ()->getConnexion ();

		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute(array(
					'codeBarre' => $codeBarre
				));
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$produit = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $produit;
	}

	public function update($produit) {
		$success = false;
		$query = 'UPDATE tproduit SET  nom_produit = :nom, prix = :prix WHERE pk_code_barre_produit = :id';
		$connection = $this->getDao ()->getConnexion ();
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$success = $statement->execute ( array (
						'id' => $produit->getCodeBarre (),
						'nom' => $produit->getNom (),
						'prix' => $produit->getPrix()
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}

		return $success;
	}

	public function delete($produit) {
		$success = false;
		$query1 = 'DELETE FROM tproduit_alimentaire WHERE pkfk_code_barre_produit = :id';
		$query2 = 'DELETE FROM tproduit_boisson WHERE pkfk_code_barre_produit = :id';
		$query3 = 'DELETE FROM tproduit_alimentaire WHERE pkfk_code_barre_produit = :id';
		$query4 = 'DELETE FROM tproduit WHERE pk_code_barre_produit = :id';
		try {
			$connection = $this->getDao ()->getConnexion ();
			if ($produit instanceof ProduitAlimentaire) {
				if (! is_null ( $connection )) {
					$statement = $connection->prepare ( $query1 );
					$success = $statement->execute ( array (
							'id' => $produit->getCodeBarre ()
					) );
					$statement = null;
					$connection = null;
				}
			} else if ($produit instanceof ProduitBoisson){
				$connection = $this->getDao ()->getConnexion ();
				if (! is_null ( $connection )) {
					$statement = $connection->prepare ( $query2 );
					$success = $statement->execute ( array (
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
					$success = $statement->execute ( array (
							'id' => $produit->getCodeBarre ()
					) );
					$statement = null;
					$connection = null;
				}
			}
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				$statement = $connection->prepare ( $query4 );
				$success = $statement->execute ( array (
						'id' => $produit->getCodeBarre ()
				) );
				$statement = null;
				$connection = null;
			}
		} catch ( \PDOException $e ) {
			throw $e;
		}

		return $success;
	}
	public static function bind($donnees) {
		$produit = null;

		if (array_key_exists('palcod', $donnees) && !is_null($donnees ['palcod'])) {
			$produit = new ProduitAlimentaire();
		} elseif (array_key_exists('pbcod', $donnees) && !is_null($donnees ['pbcod'])) {
			$produit = new ProduitBoisson();
		} else {
			$produit = new ProduitAutre();
		}

		$produit->setCodeBarre($donnees['codebarre']);
		$produit->setNom($donnees['nomproduit']);
		$produit->setPrix($donnees['prix']);

		return $produit;
	}
}


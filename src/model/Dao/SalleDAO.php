<?php

namespace model\Dao;

use \PDO;
use model\Entite\Salle;

class SalleDAO {
	private $dao;
	public function __construct($dao) {
		$this->dao = $dao;
	}
	private function getDao() {
		return $this->dao;
	}

	public function create($salle) {
		$query = 'INSERT INTO tsalle(pk_nom_salle, nb_place) VALUES(:nom, :nbplace)';
	
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'nom' => $salle->getNom(),
						'nbplace' => $salle->getNbPlaces()
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}

	public function find($nom) {
		$salle = null;
		$connection = $this->getDao ()->getConnexion ();
		$query = 'SELECT *' . ' FROM tsalle s'.
		' WHERE p.pk_nom_salle= :nom';
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'nom' => $nom 
				) );
				
				if ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$salle = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $salle;
	}
	public function findAll() {
		$salles = array ();
		$query = 'SELECT *' . ' FROM tsalle s';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$salle = self::bind ( $donnees );
					array_push ( $salles, $salle );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $salles;
	}

	

	public function update($salle) {
		$query = 'UPDATE tproduit SET  nb_place = :nbplace WHERE pk_nom_salle = :nom';
		$connection = $this->getDao ()->getConnexion ();
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'nom' => $salle->getNom (),
						'nbplace' => $salle->getNbPlaces()
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}

	public function delete($salle) {
		$query = 'DELETE FROM tsalle WHERE pk_nom_salle = :nom';
		
		try {
			$connection = $this->getDao ()->getConnexion ();
			if (! is_null ( $connection )) {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'nom' => $salle->getNom () 
				) );
				$statement = null;
				$connection = null;
			}
		} catch ( \PDOException $e ) {
			throw $e;
		}
	}

	public static function bind($donnees) {
		$salle = new Salle ();
		$salle->setNom ( $donnees ['pk_nom_salle'] );
		$salle->setNbPlaces( $donnees ['nb_place'] );
		return $salle;
	}
}


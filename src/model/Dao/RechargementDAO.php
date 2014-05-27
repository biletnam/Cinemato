<?php

namespace model\Dao;

use \PDO;

use model\Entite\Personne;
use model\Entite\Rechargement;

class RechargmentDAO {
	private $dao;
	public function __construct($dao) {
		$this->dao = $dao;
	}
	private function getDao() {
		return $this->dao;
	}
	public function find($id) {
		$rechargement = null;
		$query = 'SELECT * FROM trechargement WHERE pk_id_rechargement = :id';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				
				if ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$rechargement = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		
		return $rechargement;
	}
	public function findAll() {
		$rechargements = array ();
		$query = 'SELECT * FROM trechargement';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute (array());
				while ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$rechargement =  self::bind ( $donnees );
					array_push ( $rechargements, $rechargement );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $rechargements;
	}
	public function update($rechargement) {
		$query = 'UPDATE trechargement SET nombre_place = :nbPl, prix_unitaire = :prix  WHERE pk_id_rechargement = :id';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $rechargement->getId (),
						'nbPl' => $rechargement->getNombrePlace (),
						'prix' => $rechargement->getPrixUnitaire () 
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}
	public function delete($rechargement) {
		$query = 'DELETE FROM trechargement WHERE id = :id';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $rechargement->getId () 
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}
	public function findAllByAbonne($abonne) {
		$rechargements = array ();
		$query = 'SELECT * FROM trechargement r WHERE pkfk_id_personne_abonne = :idAbonne';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'idAbonne' => $abonne->getId () 
				) );
				while ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$rechargement =  self::bind ( $donnees );
					array_push ( $rechargements, $rechargement );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $rechargements;
	}
	public static function bind($donnees) {
		$rechargement = new Rechargement ();
		$rechargement->setId ( $donnees ['id'] );
		$rechargement->setNombrePlace ( $donnees ['nombre_place'] );
		$rechargement->setPrixUnitaire ( $donnees ['prix_unitaire'] );
		return $rechargement;
	}
}
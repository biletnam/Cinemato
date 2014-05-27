<?php

namespace model\Dao;

use \PDO;
use model\Entite\Personne;
use model\Entite\PersonneAbonne;
use model\Entite\PersonneVendeur;
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
		$personne = null;
		$query = 'SELECT *' . ' FROM tpersonne p' . ' LEFT JOIN tabonne a ON a.pkfk_id_personne = p.pk_id_personne' . ' LEFT JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne' . ' WHERE p.pk_id_personne = :id';
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				
				if ($donnees = $statement->fetch ( PDO::FETCH_ASSOC )) {
					$personne = self::bind ( $donnees );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $personne;
	}
	public function findAll() {
		$personnes = array ();
		$query = 'SELECT *' . ' FROM tpersonne p' . ' LEFT JOIN tabonne a ON a.pkfk_id_personne = p.pk_id_personne' . ' LEFT JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne';
		$connection = $this->getDao ()->getConnexion ();
		
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $id 
				) );
				while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
					$personne = self::bind ( $donnees );
					array_push ( $personnes, $personne );
				}
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
		return $personnes;
	}
	public function update($personne) {
		$query1 = 'UPDATE tabonne SET place_restante = :placeRest WHERE fkpk_id_personne = :id';
		$query2 = 'UPDATE tpersonne SET nom : :nom, prenom = :prenom WHERE pk_id_personne = :id';
		$connection = $this->getDao ()->getConnexion ();
		if ($personne instanceof PersonneAbonne) {
			if (! is_null ( $connection )) {
				try {
					$statement = $connection->prepare ( $query1 );
					$statement->execute ( array (
							'id' => $personne->getId (),
							'placeRest' => $personne->getPlaceRestante()
					) );
				} catch ( \PDOException $e ) {
					throw $e;
				}
			}
			//TODO FAIRE LES RECHARGES
		}
		$statement = null;
		$connection = null;
		$connection = $this->getDao ()->getConnexion ();
		if (! is_null ( $connection )) {
			try {
				$statement = $connection->prepare ( $query );
				$statement->execute ( array (
						'id' => $personne->getId (),
						'nbPl' => $rechargement->getNombrePlace (),
						'prix' => $rechargement->getPrixUnitaire () 
				) );
			} catch ( \PDOException $e ) {
				throw $e;
			}
		}
	}
	public function delete($personne){
		
	}
	public static function bind($donnes) {
		if ($donnes ['a.pkfk_id_personne'] == null) {
			$personne = new PersonneVendeur ();
			$personne->setId ( $donnes ['p.pk_id_personne'] );
		} else {
			$personne = new PersonneAbonne ();
			$personne->setId ( $donnes ['p.pk_id_personne'] );
			$personne->setPlaceRestante ( $donnes ['a.place_restante'] );
			$personne->setRecharges ( $this->getDao ()->getRechargementDAO ()->findAllByAbonne ( $personne ) );
		}
		$personne->setNom ( $donnes ['p.nom'] );
		$personne->setPrenom ( $donnes ['p.prenom'] );
	}
}


<?php

namespace model\Dao;

use \DateTime;

use \PDO;

use model\Entite\Seance;

class SeanceDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function find($dateSeance, $salle) {
    	$seance = null;
    	
    	$query = "SELECT * FROM tseance WHERE pk_timestamp_seance = :dateSeance, pkfk_nom_salle = :nomSalle";
    	$connection = $this->getDao()->getConnexion();
    	
    	if (is_null($connection)) {
    		return;
    	}
    	
    	$statement = $connection->prepare($query);
    	$statement->execute(array(
    			'dateSeance' => $dateSeance,
    			'nomSalle' => $salle->getNom()
    	));
    	
    	if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
    		$seance = $this->bind($donnees);
    	}
    	
    	return $seance;
    }
    
    public function findAll() {
    	$seances = array ();
    	$query = 'SELECT * FROM tseance';
    	$connection = $this->getDao ()->getConnexion ();
    	
    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array () );
    			while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
    				$seance = $this->bind ( $donnees );
    				array_push ( $seances, $seance );
    			}
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}
    	return $seances;
    }
    
    public function create($seance) {
    	$query = 'INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage) VALUES(:dateSeance, :nomSalle, :nomFilm, :doublage)';
    	$connection = $this->getDao ()->getConnexion ();
    	
    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array (
    					'dateSeance' => $seance->getDateSeance(),
    					'nomSalle' => $seance->getSalle()->getNom(),
    					'nomFilm' => $seance->getFilm()->getId(),
    					'doublage' => $seance->getDoublage()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}
    }
    
    public function update($seance){
    	$query = 'UPDATE tseance SET doublage = :doublage, fk_id_film = :nomFilm WHERE pk_timestamp_seance = :dateSeance, pkfk_nom_salle = :nomSalle ';
    	$connection = $this->getDao ()->getConnexion ();
    	
    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array (
    					'dateSeance' => $seance->getDateSeance(),
    					'nomSalle' => $seance->getSalle()->getNom(),
    					'nomFilm' => $seance->getFilm()->getId(),
    					'doublage' => $seance->getDoublage()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}
    }
    public function delete($seance){
    	$query = 'DELETE FROM tseance WHERE pk_timestamp_seance = :dateSeance, pkfk_nom_salle = :nomSalle';
    	$connection = $this->getDao ()->getConnexion ();
    	
    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array (
    					'dateSeance' => $seance->getDateSeance(),
    					'nomSalle' => $seance->getSalle()->getNom()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}
    }
    public function findSeancesOfTheWeek( $offsetWeek) {
    	$connection = $this->getDao()->getConnexion();
    	 
    	if (!is_null($connection)) {
    		$query = 'SELECT *'.
    		' FROM tseance '.
    		'WHERE pk_timestamp_seance '.
    		'BETWEEN date_trunc(\'week\', (NOW() + INTERVAL \''.$offsetWeek.' week\')) + INTERVAL \'2 day\' '.
    		'AND date_trunc(\'week\', (NOW() + INTERVAL \''.($offsetWeek +1).' week\')) + INTERVAL \'2 day\'';
    		$intervalStartWeek = $offsetWeek;
    		$intervalEndWeek = $offsetWeek +1;
    		$seances = array();
    		exit(print($query));
    		try {
    			$statement = $connection->prepare($query);
    			$statement->execute();
    			 
	    		while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
	     				$seance = $this->bind ( $donnees );
	    				array_push ( $seances, $seance );
	    			}
    		}
    		catch (PDOException $e) {
    			throw $e;
    		}
    	}
    	exit(print_r($seances, true));
    	return $seances;
    }
    
    public function bind($donnees){
    	$seance = new Seance();
    	$seance->setDateSeance($donnees['pk_timestamp_seance']);
    	$seance->setSalle($this->getDao()->getSalleDAO()->find($donnees['pkfk_nom_salle']));
    	$seance->setFilm($this->getDao()->getFilmDAO()->find($donnees['fk_id_film']));
    	$seance->setDoublage($donnees['doublage']);
    }
    
}
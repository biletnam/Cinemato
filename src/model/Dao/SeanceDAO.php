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

        $query = "SELECT * FROM tseance WHERE pk_timestamp_seance = :dateSeance AND pkfk_nom_salle = :nomSalle";
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $statement = $connection->prepare($query);
            $statement->execute(array(
                'dateSeance' => $dateSeance->format('Y-m-d H:i:s'),
                'nomSalle' => $salle->getNom()
            ));

            if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                $seance = $this->bind($donnees);
            }
        }

        return $seance;
    }

    public function findAll() {
    	$seances = array ();
    	$query = 'SELECT * FROM tseance ORDER BY pk_timestamp_seance';
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

    public function findByFilm($film) {
        $seances = array ();
        $query = 'SELECT * FROM tseance WHERE fk_id_film = :film ORDER BY pk_timestamp_seance';
        $connection = $this->getDao ()->getConnexion ();

        if (! is_null ( $connection )) {
            try {
                $statement = $connection->prepare ( $query );
                $statement->execute ( array ('film' => $film->getId()) );
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

    public function findByFilmAndWeek($film, $offsetWeek) {
        $seances = array ();
        $query = "SELECT * FROM tseance WHERE fk_id_film = :film and pk_timestamp_seance ".
            " BETWEEN date_trunc('week',".
                " (NOW() + ".
                " CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN INTERVAL '".($offsetWeek + 0)." week'".
                " WHEN EXTRACT(DOW FROM NOW()) = 1 THEN INTERVAL '".($offsetWeek - 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 2 THEN INTERVAL '".($offsetWeek - 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 3 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 4 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 5 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 6 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " END".
                " )) + INTERVAL '2 day'".
                " AND date_trunc('week',".
                " (NOW() + ".
                " CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN INTERVAL '".($offsetWeek + 0 + 1)." week'".
                " WHEN EXTRACT(DOW FROM NOW()) = 1 THEN INTERVAL '".($offsetWeek - 1 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 2 THEN INTERVAL '".($offsetWeek - 1 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 3 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 4 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 5 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 6 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " END".
                " )) + INTERVAL '2 day'";
        $connection = $this->getDao ()->getConnexion ();

        if (! is_null ( $connection )) {
            try {
                $statement = $connection->prepare ( $query );
                $statement->execute ( array ('film' => $film->getId()) );
                //exit(var_dump($statement));
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
					'dateSeance' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
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
    	$query = 'UPDATE tseance SET doublage = :doublage, fk_id_film = :nomFilm WHERE pk_timestamp_seance = :dateSeance AND pkfk_nom_salle = :nomSalle ';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array (
					'dateSeance' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
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
    	$query = 'DELETE FROM tseance WHERE pk_timestamp_seance = :dateSeance AND pkfk_nom_salle = :nomSalle';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array (
					'dateSeance' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
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
    		$query = "SELECT *".
    		" FROM tseance ".
    		" WHERE pk_timestamp_seance ".
    		" BETWEEN date_trunc('week',".
                " (NOW() + ".
                " CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN INTERVAL '".($offsetWeek + 0)." week'".
                " WHEN EXTRACT(DOW FROM NOW()) = 1 THEN INTERVAL '".($offsetWeek - 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 2 THEN INTERVAL '".($offsetWeek - 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 3 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 4 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 5 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 6 THEN INTERVAL '".($offsetWeek + 0)." week' ".
                " END".
                " )) + INTERVAL '2 day'".
                " AND date_trunc('week',".
                " (NOW() + ".
                " CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN INTERVAL '".($offsetWeek + 0 + 1)." week'".
                " WHEN EXTRACT(DOW FROM NOW()) = 1 THEN INTERVAL '".($offsetWeek - 1 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 2 THEN INTERVAL '".($offsetWeek - 1 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 3 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 4 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 5 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " WHEN EXTRACT(DOW FROM NOW()) = 6 THEN INTERVAL '".($offsetWeek + 0 + 1)." week' ".
                " END".
                " )) + INTERVAL '2 day'";
    		$intervalStartWeek = $offsetWeek;
    		$intervalEndWeek = $offsetWeek +1;
    		$seances = array();

    		try {
    			$statement = $connection->prepare($query);
    			$statement->execute();

	    		while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
	     				$seance = $this->bind($donnees);
	    				array_push ($seances, $seance);
	    			}
    		}
    		catch (PDOException $e) {
    			throw $e;
    		}
    	}
    	return $seances;
    }

    public function bind($donnees){
    	$seance = new Seance();
    	$seance->setDateSeance(new \DateTime($donnees['pk_timestamp_seance']));
    	$seance->setSalle($this->getDao()->getSalleDAO()->find($donnees['pkfk_nom_salle']));
    	$seance->setFilm($this->getDao()->getFilmDAO()->find($donnees['fk_id_film']));
    	$seance->setDoublage($donnees['doublage']);

    	return $seance;
    }

}

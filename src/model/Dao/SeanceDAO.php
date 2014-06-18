<?php

namespace model\Dao;

use \DateTime;
use \PDO;
use \PDOException;

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

    public function find($id) {
    	$seance = null;

        $connection = $this->getDao()->getConnexion();

    	if (!is_null($connection)) {
            $query = 'SELECT * FROM tseance WHERE pk_id_seance = :id';

            $statement = $connection->prepare($query);
            $statement->execute(array(
                'id' => $id
            ));

            if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                $seance = $this->bind($donnees);
            }
        }

    	return $seance;
    }

    public function findAll() {
    	$seances = array ();
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'SELECT * FROM tseance ORDER BY timestamp_seance';

    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array () );
    			while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
    				$seance = $this->bind ( $donnees );
    				array_push ( $seances, $seance );
    			}
    		} catch ( PDOException $e ) {
    			throw $e;
    		}
    	}

    	return $seances;
    }

    public function findByFilm($film) {
        $seances = array ();
        $connection = $this->getDao ()->getConnexion ();

        if (! is_null ( $connection )) {
            $query = 'SELECT * FROM tseance WHERE fk_id_film = :film ORDER BY timestamp_seance';

            try {
                $statement = $connection->prepare ( $query );
                $statement->execute ( array ('film' => $film->getId()) );
                while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
                    $seance = $this->bind ( $donnees );
                    array_push ( $seances, $seance );
                }
            } catch ( PDOException $e ) {
                throw $e;
            }
        }
        return $seances;
    }

    public function findByFilmAndWeek($film, $offsetWeek) {
        $seances = array ();
        $query = "SELECT * FROM tseance WHERE fk_id_film = :film and timestamp_seance ".
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
            " )) + INTERVAL '2 day' ORDER BY timestamp_seance";
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

            } catch ( PDOException $e ) {
                throw $e;
            }
        }
        return $seances;
    }

    public function create($seance) {
        $success = false;
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'INSERT INTO tseance(pk_id_seance, timestamp_seance, fk_nom_salle, fk_id_film, doublage) VALUES(nextval(\'sequence_seance\'), :dateSeance, :nomSalle, :idFilm, :doublage)';

    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
					'dateSeance' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
					'nomSalle' => $seance->getSalle()->getNom(),
					'idFilm' => $seance->getFilm()->getId(),
					'doublage' => $seance->getDoublage()
    			) );
    		} catch ( PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }

    public function update($seance){
        $success = false;
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'UPDATE tseance SET timestamp_seance = :dateSeance, fk_nom_salle = :nomSalle, fk_id_film = :idFilm, doublage = :doublage WHERE pk_id_seance = :id';

    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
                    'id' => $seance->getId(),
                    'dateSeance' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
                    'nomSalle' => $seance->getSalle()->getNom(),
                    'idFilm' => $seance->getFilm()->getId(),
                    'doublage' => $seance->getDoublage()
    			) );
    		} catch ( PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }
    public function delete($seance) {
        $success = false;
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'DELETE FROM tseance WHERE pk_id_seance = :id';

    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
					'id' => $seance->getId()
    			) );
    		} catch ( PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }
    public function findSeancesOfTheWeek( $offsetWeek) {

    	$connection = $this->getDao()->getConnexion();

    	if (!is_null($connection)) {
    		$query = "SELECT *".
        		" FROM tseance ".
        		" WHERE timestamp_seance ".
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
                " )) + INTERVAL '2 day' ORDER BY timestamp_seance";
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
        $seance->setId($donnees['pk_id_seance']);
    	$seance->setDateSeance(new DateTime($donnees['timestamp_seance']));
    	$seance->setSalle($this->getDao()->getSalleDAO()->find($donnees['fk_nom_salle']));
    	$seance->setFilm($this->getDao()->getFilmDAO()->find($donnees['fk_id_film']));
    	$seance->setDoublage($donnees['doublage']);

    	return $seance;
    }

}

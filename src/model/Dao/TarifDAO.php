<?php

namespace model\Dao;

use \PDO;

use model\Entite\Tarif;

class TarifDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function find($id) {
        $tarif = null;

        $query = "SELECT * FROM ttarif WHERE pk_nom_tarif = :id";
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $statement = $connection->prepare($query);
            $statement->execute(array(
                'id' => $id
            ));

            if ($tarifData = $statement->fetch(PDO::FETCH_ASSOC)) {
                $tarif = $this->bind($tarifData);
            }
        }

        return $tarif;
    }

    public function findAll() {
    	$tarifs = array ();
    	$query = 'SELECT * FROM ttarif';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array () );
    			while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
    				$tarif = $this->bind($donnees);
    				array_push ( $tarifs, $tarif );
    			}
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}
    	return $tarifs;
    }

    public function create($tarif) {
        $success = false;
    	$query = 'INSERT INTO ttarif(pk_nom_tarif, tarif) VALUES(:nomTarif, :tarif)';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
    					'nomTarif' => $tarif->getNom(),
    					'tarif' => $tarif->getTarif()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }

    public function update($tarif) {
        $success = false;
    	$query = 'UPDATE ttarif SET tarif = :tarif WHERE pk_nom_tarif = :nomTarif';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
    					'nomTarif' => $tarif->getNom(),
    					'tarif' => $tarif->getTarif()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }
    public function delete($tarif) {
        $success = false;
    	$query = 'DELETE FROM ttarif WHERE pk_nom_tarif = :nomTarif';
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
    		try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
    					'nomTarif' => $tarif->getNom()
    			) );
    		} catch ( \PDOException $e ) {
    			throw $e;
    		}
    	}

        return $success;
    }

    protected function bind($donnees) {
        $tarif = new Tarif();
        $tarif->setNom($donnees['pk_nom_tarif']);
        $tarif->setTarif(floatval($donnees['tarif']));

        return $tarif;
    }
}

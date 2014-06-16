<?php
namespace model\Dao;

use \PDO;
use model\Entite\Personne;
use model\Entite\Rechargement;

class RechargementDAO
{

    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    private function getDao()
    {
        return $this->dao;
    }

    public function find($id)
    {
        $rechargement = null;
        $query = 'SELECT * FROM trechargement WHERE pk_id_rechargement = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));

                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $rechargement = self::bind($donnees);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $rechargement;
    }

    public function findAll()
    {
        $rechargements = array();
        $query = 'SELECT * FROM trechargement';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array());
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $rechargement = self::bind($donnees);
                    array_push($rechargements, $rechargement);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $rechargements;
    }

    public function update($rechargement)
    {
        $success = false;
        $query = 'UPDATE trechargement SET nombre_place = :nbPl, prix_unitaire = :prix, places_utilises = :plcUtil   WHERE pk_id_rechargement = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $success = $statement->execute(array(
                    'id' => $rechargement->getId(),
                    'nbPl' => (is_null($rechargement->getNombrePlace())) ? 0 : $rechargement->getNombrePlace(),
                    'prix' => $rechargement->getPrixUnitaire(),
                    'plcUtil' => $rechargement->getPlacesUtilise()
                ));
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $success;
    }

    public function delete($rechargement)
    {
        $success = false;
        $query = 'DELETE FROM trechargement WHERE pk_id_rechargement = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $success = $statement->execute(array(
                    'id' => $rechargement->getId()
                ));
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $success;
    }

    public function findAllByAbonne($abonne)
    {
        $rechargements = array();
        $query = 'SELECT * FROM trechargement r WHERE pkfk_id_personne_abonne = :idAbonne';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'idAbonne' => $abonne->getId()
                ));
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $rechargement = self::bind($donnees);
                    array_push($rechargements, $rechargement);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $rechargements;
    }

    public function create(&$recharge, $personne)
    {
        $query = "SELECT nextval('sequence_rechargement') as id";
        $query2 = 'INSERT INTO trechargement(pk_id_rechargement, pkfk_id_personne_abonne, nombre_place, prix_unitaire, places_utilises) VALUES(:idRe,:id, :nbPl, :prix, :plcUtil )';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();
                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $recharge->setId($donnees['id']);
                }
                $statement = null;
                $statement = $connection->prepare($query2);
                $statement->execute(array(
                    'idRe' => $recharge->getId(),
                    'id' => $personne->getId(),
                    'nbPl' => $recharge->getNombrePlace(),
                    'prix' => $recharge->getPrixUnitaire(),
                    'plcUtil' =>($rechargement->getNombrePlace()==null)?0:$rechargement->getNombrePlace(),
                ));
            } catch (\PDOException $e) {
                throw $e;
            }
        }
    }

    public function deleteRechargeOrphelineUser($personne)
    {
        $success = false;
        $rechargesIdHolder = '';
        $query = 'DELETE FROM trechargement'
            . ' WHERE pkfk_id_personne_abonne = :id';
        $paramQuery = array(
            'id' => $personne->getId()
        );

        if ($personne->getRecharges()) {
            foreach ($personne->getRecharges() as $i => $recharge) {
                $rechargesIdHolder = $rechargesIdHolder . ':idRecharge' . $i . ', ';
                $paramQuery['idRecharge' . $i] = $recharge->getId();
            }
            $rechargesIdHolder = substr($rechargesIdHolder, 0, - 2);
            $query .= " AND pk_id_rechargement NOT IN ($rechargesIdHolder)";
        }

        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $success = $statement->execute($paramQuery);
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $success;
    }

    public static function bind($donnees)
    {
        $rechargement = new Rechargement();
        $rechargement->setId($donnees['pk_id_rechargement']);
        $rechargement->setPlacesUtilise($donnees['places_utilises']);
        $rechargement->setNombrePlace($donnees['nombre_place']);
        $rechargement->setPrixUnitaire($donnees['prix_unitaire']);

        return $rechargement;
    }
}

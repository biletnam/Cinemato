<?php

namespace model\Dao;

use \PDO;
use \PDOException;

use model\Entite\Distributeur;

class DistributeurDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function findAll() {
        $distributeurs = array();

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tdistributeur';

            try {
                $statement = $connection->prepare($query);
                $statement->execute();

                $distributeurRows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($distributeurRows as $distributeurData) {
                    $distributeurs[] = self::map($distributeurData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $distributeurs;
    }

    public function find($id) {
        $distributeur = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tdistributeur WHERE pk_id_distributeur = :id';

            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));

                if ($distributeurData = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $distributeur = self::map($distributeurData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $distributeur;
    }

    public function create($distributeur) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'INSERT INTO tdistributeur (pk_id_distributeur, nom, prenom, addresse, tel) VALUES(nextval(sequence_distributeur), :nom, :prenom, :addresse, :tel)';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'nom' => $distributeur->getNom(),
                    'prenom' => $distributeur->getPrenom(),
                    'adresse' => $distributeur->getAddrese(),
                    'tel' => $distributeur->getTelephone()
                ));
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function update($distributeur) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'UPDATE tdistributeur SET nom = :nom, prenom = :prenom, addresse = :addresse, tel = :tel WHERE pk_id_distributeur = :id';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'id' => $distributeur->getId(),
                    'nom' => $distributeur->getNom(),
                    'prenom' => $distributeur->getPrenom(),
                    'adresse' => $distributeur->getAddrese(),
                    'tel' => $distributeur->getTelephone()
                ));
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function delete(&$distributeur) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'DELETE FROM tdistributeur WHERE pk_id_distributeur = :id LIMIT 0, 1';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'id' => $distributeur->getId()
                ));

                if ($check) {
                    $distributeur = null;
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function map($donnees) {
        $distributeur = new Distributeur($donnees['nom'], $donnees['prenom'], $donnees['adresse'], $donnees['tel']);
        $distributeur->setId($donnees['pk_id_distributeur']);

        return $distributeur;
    }
}


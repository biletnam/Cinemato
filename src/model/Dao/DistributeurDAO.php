<?php

namespace model\Dao;

use \PDO;

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

            $statement = $connection->prepare($query);
            $statement->execute();

            $distributeurRows = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($distributeurRows as &$distributeurData) {
                $distributeurs[] = Distributeur::mapFromData($distributeurData);
            }
        }

        return $distributeurs;
    }

    public function find($id) {
        $distributeur = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tdistributeur WHERE pk_id_distributeur = :id';

            $statement = $connection->prepare($query);
            $statement->execute(array(
                'id' => $id
            ));

            if ($distributeurData = $statement->fetch(PDO::FETCH_ASSOC)) {
                $distributeur = Distributeur::mapFromData($distributeurData);
            }
        }

        return $distributeur;
    }
}


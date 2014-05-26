<?php

namespace model\Dao;

use \DateTime;
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

    public function find($id) {
        $distributeur = null;

        $query = "SELECT * FROM tdistributeur WHERE pk_id_distributeur = :id";
        $connection = $this->getDao()->getConnexion();

        if (is_null($connection)) {
            return;
        }

        $statement = $connection->prepare($query);
        $statement->execute(array(
            'id' => $id
        ));

        if ($distributeurData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $distributeur = Distributeur::mapFromData($distributeurData);
        }

        return $distributeur;
    }
}


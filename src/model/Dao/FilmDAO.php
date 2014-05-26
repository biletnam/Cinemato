<?php

namespace model\Dao;

use \DateTime;
use \PDO;

use model\Entite\Film;

class FilmDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function findAll() {
        $films = array();

        $query = "SELECT * FROM tfilm";
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $statement = $connection->prepare($query);
            $statement->execute();

            $filmRows = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($filmRows as &$filmData) {
                $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                $films[] = Film::mapFromData($filmData);
            }
        }

        return $films;
    }

    public function find($id) {
        $film = null;

        $query = "SELECT * FROM tfilm WHERE pk_id_film = :id";
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $statement = $connection->prepare($query);
            $statement->execute(array(
                'id' => $id
            ));

            if ($filmData = $statement->fetch(PDO::FETCH_ASSOC)) {
                $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                $film = Film::mapFromData($filmData);
            }
        }

        return $film;
    }
}


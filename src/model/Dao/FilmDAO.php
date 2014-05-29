<?php

namespace model\Dao;

use \DateTime;
use \PDO;

use model\Entite\Film;
use model\Query\SelectQuery;

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

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = new SelectQuery($connection);
            $query->setTable('tfilm f')
                ->setFields('*');

            $filmRows = $query->fetchAll(array(), PDO::FETCH_ASSOC);

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

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tfilm WHERE pk_id_film = :id';

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

    public function create($film) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = "INSERT INTO tfilm (titre, date_sortie, age_min, fk_nom_genre, fk_id_distributeur) VALUES(:titre, :dateDeSortie, :ageMinimum, :genre_nom, :distributeur_id)";

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'titre' => $film->getTitre(),
                    'dateDeSortie' => $film->getDateDeSortie()->format('d m Y'),
                    'ageMinimum' => $film->getAgeMinimum(),
                    'genre_nom' => $film->getGenre()->getNom(),
                    'distributeur_id' => $film->getDistributeur()->getId()
                ));
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }
}


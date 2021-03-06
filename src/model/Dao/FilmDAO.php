<?php

namespace model\Dao;

use \DateTime;
use \PDO;
use \PDOException;

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

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tfilm';

            try {
                $statement = $connection->prepare($query);
                $statement->execute();

                $filmRows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filmRows as &$filmData) {
                    $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                    $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                    $films[] = $this->map($filmData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $films;
    }

    public function find($id) {
        $film = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tfilm WHERE pk_id_film = :id';

            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));

                if ($filmData = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                    $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                    $film = $this->map($filmData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $film;
    }

    public function findAllByTitle($titre) {
        $films = array();

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tfilm'
                . ' WHERE LOWER(titre)'
                . ' LIKE LOWER(:titre)';

            try {
                $statement = $connection->prepare($query);
                $titre = '%' . $titre . '%';
                $statement->execute(array(
                    'titre' => $titre
                ));
                

                $filmRows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filmRows as &$filmData) {
                    $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                    $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                    $films[] = $this->map($filmData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $films;
    }

    public function create($film) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'INSERT INTO tfilm (pk_id_film, titre, date_sortie, age_min, fk_nom_genre, fk_id_distributeur) VALUES (nextval(\'sequence_film\'), :titre, :dateDeSortie, :ageMinimum, :genre_nom, :distributeur_id)';

            try {
                $statement = $connection->prepare($query);
                $params = array(
                    'titre' => $film->getTitre(),
                    'dateDeSortie' => $film->getDateDeSortie()->format('d m Y'),
                    'ageMinimum' => $film->getAgeMinimum(),
                    'genre_nom' => $film->getGenre()->getNom(),
                    'distributeur_id' => $film->getDistributeur()->getId()
                );
                $check = $statement->execute($params);
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function update($film) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'UPDATE tfilm SET titre = :titre, date_sortie = :dateDeSortie, age_min = :ageMinimum, fk_nom_genre = :genre_nom, fk_id_distributeur = :distributeur_id WHERE pk_id_film = :id';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'id' => $film->getId(),
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

    public function delete(&$film) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'DELETE FROM tfilm WHERE pk_id_film = :id';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'id' => $film->getId()
                ));

                if ($check) {
                    $film = null;
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function map($donnees) {
        $film = new Film($donnees['titre'], new \DateTime(($donnees['date_sortie'].' 00:00:00')), $donnees['age_min'], $donnees['genre'], $donnees['distributeur']);
        $film->setId($donnees['pk_id_film']);

        return $film;
    }

    public function findFilmSemaine($offsetWeek) {
        $films = array();

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = "SELECT *".
                " FROM tfilm f".
                " WHERE f.pk_id_film IN (SELECT s.fk_id_film FROM tseance s WHERE s.timestamp_seance".
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
                " )) + INTERVAL '2 day')";

            try {
                $statement = $connection->prepare($query);
                //exit(var_dump($statement));
                $statement->execute();
                $filmRows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filmRows as &$filmData) {
                    $filmData['genre'] = $this->getDao()->getGenreDAO()->find($filmData['fk_nom_genre']);
                    $filmData['distributeur'] = $this->getDao()->getDistributeurDAO()->find($filmData['fk_id_distributeur']);

                    $films[] = $this->map($filmData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $films;
    }

}


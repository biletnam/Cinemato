<?php

namespace model\Dao;

use \PDO;
use \PDOException;

use model\Entite\Genre;

class GenreDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function findAll() {
        $genres = array();

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tgenre';

            try {
                $statement = $connection->prepare($query);
                $statement->execute();

                $genreRows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($genreRows as &$genreData) {
                    $genres[] = self::map($genreData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $genres;
    }

    public function find($nom) {
        $genre = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tgenre WHERE pk_nom_genre = :nom';

            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'nom' => $nom
                ));

                if ($genreData = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $genre = self::map($genreData);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $genre;
    }

    public function create($genre) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'INSERT INTO tgenre (pk_nom_genre) VALUES(:pk_nom_genre)';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'pk_nom_genre' => $genre->getNom()
                ));
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function delete(&$genre) {
        $check = false;
        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'DELETE FROM tgenre WHERE pk_nom_genre = :pk_nom_genre';

            try {
                $statement = $connection->prepare($query);
                $check = $statement->execute(array(
                    'pk_nom_genre' => $genre->getNom()
                ));

                if ($check) {
                    $genre = null;
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        return $check;
    }

    public function map($donnees) {
        $genre = new Genre($donnees['pk_nom_genre']);

        return $genre;
    }
}


<?php

namespace model\Dao;

use \PDO;

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

            $statement = $connection->prepare($query);
            $statement->execute();

            $genreRows = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($genreRows as &$genreData) {
                $genres[] = Genre::mapFromData($genreData);
            }
        }

        return $genres;
    }

    public function find($nom) {
        $genre = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = 'SELECT * FROM tgenre WHERE pk_nom_genre = :nom';

            $statement = $connection->prepare($query);
            $statement->execute(array(
                'nom' => $nom
            ));

            if ($genreData = $statement->fetch(PDO::FETCH_ASSOC)) {
                $genre = Genre::mapFromData($genreData);
            }
        } else {
            exit(var_dump('connexion null'));
        }

        return $genre;
    }
}


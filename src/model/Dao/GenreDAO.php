<?php

namespace model\Dao;

use \DateTime;
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

    public function find($nom) {
        $genre = null;

        $query = "SELECT * FROM tgenre WHERE pk_nom_genre = :nom";
        $connection = $this->getDao()->getConnexion();

        if (is_null($connection)) {
            return;
        }

        $statement = $connection->prepare($query);
        $statement->execute(array(
            'nom' => $nom
        ));

        if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
            $genre = new Genre();
            $genre->setNom($donnees['pk_nom_genre']);
        }

        return $genre;
    }
}


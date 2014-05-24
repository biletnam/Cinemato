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

			$films = $statement->fetchAll(PDO::FETCH_ASSOC);
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

			if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
				$film = new Film();
				$film->setId($donnees['pk_id_film']);
				$film->setTitre($donnees['titre']);
				$film->setDateDeSortie(new DateTime($donnees['date_sortie']));
				$film->setAgeMinimum($donnees['age_min']);

				$genre = $this->getDao()->getGenreDAO()->find($donnees['fk_nom_genre']);
				$film->setGenre($genre);

				$distributeur = $this->getDao()->getDistributeurDAO()->find($donnees['fk_id_distributeur']);
				$film->setDistributeur($distributeur);
			}
		}

		return $film;
	}
}


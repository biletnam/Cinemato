<?php

namespace model\Dao;

use model\Dao\Film;

class FilmDAO
{
	private $dao;

	public function __construct($dao) {
		$this->dao = $dao;
	}

	private function getDao() {
		return $this->dao;
	}

	public function find($id) {
		// Film instance
		$film = null;

		$query = "SELECT * FROM tfilm WHERE pk_id_film = :id";
		$connection = $this->getDao()->getConnexion();

		if (is_null($connection)) {
			return;
		}

		$statement = $connection->prepare($query);
		$request = $statement->execute(array(
			'id' => $id
		));

		// Ici il ne devrait y avait qu'un film retourné
		if ($donnees = $request->fetch()) {
			$film = new Film();
			$film->setId($donnees['pk_id_film']);
			$film->setTitre($donnees['titre']);
			$film->setDateDeSortie($donnees['date_sortie']);
			$film->setAgeMinimum($donnees['age_min']);

			$genre = $this->getDao()->getGenreDAO()->find($donnees['fk_nom_genre']);
			$film->setGenre($genre);

			$distributeur = $this->getDao()->getDistributeurDAO()->find($donnees['fk_id_distributeur']);
			$film->setDistributeur($distributeur);
		}

		return film;
	}
}


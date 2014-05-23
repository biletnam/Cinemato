<?php
namespace model\Dao;

class FilmDAO{
	private $instanceDAO;
	
	public function __construct($instanceDAO){
		$this->instanceDAO = $instanceDAO;
	}
	
	public function find($id){
		$query = "SELECT * FROM tfilm WHERE pk_id_film = :id";
		$connectioPDO = $this->instanceDAO->getConnexion();
		if($connectioPDO == null)
			return;
		$statement = $connectioPDO->prepare($query);
		$statement->execute(array('id'=> $id));
		//Ici il ne devrait y avait qu'un film retourné
		while ($donnees = $req->fetch())
		{
			$film = new Film();
			$film->setId($donnees['pk_id_film']);
			$film->setTitre($donnees['titre']);
			$film->setDateDeSortie($donnees['date_sortie']);
			$film->setAgeMinimum($donnees['age_min']);
			
			$genre = $instanceDAO->getGenreDAO()->find($donnees['fk_nom_genre']);
			$film->setGenre($genre);
			
			$distrib = $instanceDAO->getDistributeurDAO()->find($donnees['fk_id_distributeur']);
			$film->setDistributeur($distrib);
		}
		return film;
		
	}
}
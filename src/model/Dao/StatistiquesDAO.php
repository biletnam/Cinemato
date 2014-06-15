<?php

namespace model\Dao;

use \PDO;
use \PDOException;
use model\Entite\Seance;


class StatistiquesDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }
    public function getTauxOccupationSeance($seance){
        $occ = null;
        
        $query = 'SELECT tauxOccupationSeance(:date, :salle) as occ';
        $connection = $this->getDao()->getConnexion();
         
        if (is_null($connection)) {
            return;
        }
         
        $statement = $connection->prepare($query);
        $statement->execute(array(
        	'date' => $seance->getDateSeance()->format('Y-m-d H:i:s'),
            'salle' => $seance->getSalle()->getNom()
        ));
        
        if ($donne = $statement->fetch(PDO::FETCH_ASSOC)) {
            $occ = $donne['occ'];
        }
         
        return $occ;
    }
    public function getNbAbonne(){
        $nbAbonne = null;
         
        $query = 'SELECT nbAbonne() as nbabonne';
        $connection = $this->getDao()->getConnexion();
         
        if (is_null($connection)) {
            return;
        }
         
        $statement = $connection->prepare($query);
        $statement->execute();
        if ($donne = $statement->fetch(PDO::FETCH_ASSOC)) {
            $nbAbonne = $donne['nbabonne'];
        }
         
        return $nbAbonne;
        
    }
    public function getNbFilmSemaine($offset){
        $nbFilm = null;
         
        $query = 'SELECT nbFilmSemaine(:offset) as nbfilm';
        $connection = $this->getDao()->getConnexion();
         
        if (is_null($connection)) {
            return;
        }
         
        $statement = $connection->prepare($query);
        $statement->execute(array(
            'offset'=>$offset
        ));
        if ($donne = $statement->fetch(PDO::FETCH_ASSOC)) {
            $nbFilm = $donne['nbfilm'];
        }
         
        return $nbFilm;
    }
    public function getTotalEntreFilm($film){
        $entre = null;
         
        $query = 'SELECT totalEntreFilm(:film) as entre';
        $connection = $this->getDao()->getConnexion();
         
        if (is_null($connection)) {
            return;
        }
         
        $statement = $connection->prepare($query);
        $statement->execute(array(
            'film'=>$film->getId()
        ));
        if ($donne = $statement->fetch(PDO::FETCH_ASSOC)) {
            $entre = $donne['entre'];
        }
         
        return $entre;
    }
    public function getTotalRevenueFilm($film){
        $revenue = null;
         
        $query = 'SELECT revenueFilm(:film) as revenue';
        $connection = $this->getDao()->getConnexion();
         
        if (is_null($connection)) {
            return;
        }
         
        $statement = $connection->prepare($query);
        $statement->execute(array(
            'film'=>$film->getId()
        ));
        if ($donne = $statement->fetch(PDO::FETCH_ASSOC)) {
            $revenue = $donne['revenue'];
        }
         
        return $revenue;
    }
}

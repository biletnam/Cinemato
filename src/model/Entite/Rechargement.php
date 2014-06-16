<?php
namespace model\Entite;

class Rechargement
{

    private $id;

    private $nombrePlace;

    private $prixUnitaire;

    private $placesUtilise;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNombrePlace()
    {
        return $this->nombrePlace;
    }

    public function setNombrePlace($nombrePlace)
    {
        $this->nombrePlace = $nombrePlace;
        return $this;
    }

    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }
    public function getPlacesUtilise(){
        return  $this->placesUtilise;
    }
    public function setPlacesUtilise($placesUtilise){
        $this->placesUtilise = $placesUtilise;
        return $this;
    }
}

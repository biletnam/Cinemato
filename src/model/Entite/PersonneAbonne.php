<?php

namespace model\Entite;

class PersonneAbonne extends Personne
{

    private $placeRestante;

    private $recharges;

    public function __construct()
    {}

    public function getPlaceRestante()
    {
        if ($this->placeRestante == null)
            $this->placeRestante = 0;
        return $this->placeRestante;
    }

    public function setPlaceRestante($placeRestante)
    {
        $this->placeRestante = $placeRestante;
        return $this;
    }

    public function getRecharges()
    {
        if ($this->recharges == null)
            $this->recharges = array();
        return $this->recharges;
    }

    public function setRecharges($recharges)
    {
        $this->recharges = $recharges;
        return $this;
    }

    public function addRecharge($recharge)
    {
        array_push($this->getRecharges(), $recharge);
    }

    public function deleteRecharge($indice)
    {
        $recharges = $this->getRecharges();
        unset($recharges[$indice]);
        $this->setRecharges($recharges);
    }
}

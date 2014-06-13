<?php

namespace model\Entite;

class PersonneAbonne extends Personne
{

    private $placeRestante;

    private $recharges;

    public function __construct()
    {
        parent::__construct();
        $this->recharges = array();
    }

    public function getPlaceRestante()
    {
        if ($this->placeRestante == null) {
            $this->placeRestante = 0;
        }

        return $this->placeRestante;
    }

    public function setPlaceRestante($placeRestante)
    {
        $this->placeRestante = $placeRestante;

        $cmp = 0;
        foreach ($this->getRecharges() as $recharge){
            $cmp = $cmp + ($recharge->getNombrePlace()-$recharge->getPlacesUtilise());
        }

        return $cmp;
    }

    public function getRecharges()
    {
        return $this->recharges;
    }

    public function setRecharges($recharges)
    {
        $this->recharges = $recharges;

        return $this;
    }

    public function addRecharge($recharge)
    {
        if (!in_array($recharge, $this->recharges)) {
            $this->recharges[] = $recharge;
        }

        return $this;
    }

    public function deleteRecharge($indice)
    {
        // Why not unset($this->recharges[$indice]); ??
        $recharges = $this->getRecharges();
        unset($recharges[$indice]);
        $this->setRecharges($recharges);

        return $this;
    }
}

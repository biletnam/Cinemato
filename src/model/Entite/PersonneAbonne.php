<?php

namespace model\Entite;

class PersonneAbonne extends Personne
{
    private $recharges;

    public function __construct()
    {
        $this->recharges = array();
    }

    public function getPlacesRestantes()
    {
        $places = 0;
        foreach ($this->getRecharges() as $recharge) {
            $places += ($recharge->getNombrePlace() - $recharge->getPlacesUtilise());
        }

        return $places;
    }

    public function getRechargeNotEmpty()
    {
        $rechargeNotEmpty = null;

        foreach ($this->getRecharges() as $recharge) {
            if (($recharge->getNombrePlace() - $recharge->getPlacesUtilise()) > 0) {
                $rechargeNotEmpty = $recharge;
                break;
            }
        }

        return $rechargeNotEmpty;
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
        $recharges = $this->getRecharges();
        unset($recharges[$indice]);
        $this->setRecharges($recharges);

        return $this;
    }
}

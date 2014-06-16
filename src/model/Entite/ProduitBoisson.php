<?php

namespace model\Entite;

/**
 * Entité ProduitBoissons
 */
class ProduitBoisson extends Produit
{

    public function __construct()
    {
        parent::__construct(0, '', 5.00);
    }
}

<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;

use model\Dao\Dao;
use model\Entite\Salle;
use model\Entite\ProduitAlimentaire;
use model\Entite\ProduitAutre;
use model\Entite\ProduitBoissons;

// Load controllers
require_once __DIR__.'/intranet/intranet.php';
require_once __DIR__.'/public/public.php';

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $produitDao = Dao::getInstance()->getProduitDAO();
    $produits = new ProduitBoissons();
    $produits->setCodeBarre(633);
    $produits->setNom('Chouffe');
    $produits->setPrix(3.1);
    $produitDao->create($produits);
    echo '<pre>';
    return new Response(print_r($produits, true));
});

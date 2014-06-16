<?php

// Load controllers
require_once __DIR__.'/abonnes.php';
require_once __DIR__.'/tickets.php';
require_once __DIR__.'/produits.php';

$app->get('/vente', function () use ($app) {
    return $app['twig']->render('pages/vente/home.html.twig');
})->bind('vente');

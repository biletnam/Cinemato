<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;

use model\Dao\Dao;
use model\Entite\Salle;

// Load controllers
require_once __DIR__.'/intranet/intranet.php';
require_once __DIR__.'/public/public.php';

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $salleDao = Dao::getInstance()->getSalleDAO();
    $salle = $salleDao->find('Salle Zinédine Zidane');
    $salle2 = new Salle();
    $salle2->setNom('KARIM');
    $salle2->setNbPlaces(3);
    $salleDao->create($salle2);
    $salleDao->delete($salle);

    echo '<pre>';
    return new Response(print_r($salle, true));
});

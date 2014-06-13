<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Distributeur;
use forms\DistributeurForm;

$distributeursControllers = $app['controllers_factory'];

$distributeursControllers->get('/', function () use ($app) {

    return $app['twig']->render('pages/intranet/statistiques/list.html.twig', array(
    ));
})->bind('intranet-statistiques-list');

$app->mount('/intranet/statistiques', $distributeursControllers);

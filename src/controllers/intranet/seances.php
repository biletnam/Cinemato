<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Seance;
use forms\SeanceForm;

$filmsControllers = $app['controllers_factory'];

$filmsControllers->get('/', function () use ($app) {
    $seanceDao = Dao::getInstance()->getSeanceDAO();
    $seances = $seanceDao->findAll();

    return $app['twig']->render('pages/intranet/seances/list.html.twig', array(
        'entities' => $seances
    ));
})->bind('intranet-seances-list');

$filmsControllers->get('/new', function () use ($app) {
    $salleDao = Dao::getInstance()->getSalleDAO();
    $salles = $salleDao->findAll();

    $filmDao = Dao::getInstance()->getFilmDAO();
    $filmss = $filmsDao->findAll();

    $form = $app['form.factory']->create(new SeanceForm($salles, $films));

    return $app['twig']->render('pages/intranet/seances/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-seances-new');



$app->mount('/intranet/seances', $filmsControllers);

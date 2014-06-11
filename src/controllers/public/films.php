<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Film;

$filmsPublicControllers = $app['controllers_factory'];

$filmsPublicControllers->get('/', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();

    $films = $filmDao->findAll();

    return $app['twig']->render('pages/public/films/list.html.twig', array(
        'films' => $films
    ));
})->bind('public-films-list');

$filmsPublicControllers->get('/{id}', function ($id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    return $app['twig']->render('pages/public/films/detail.html.twig', array(
        'film' => $film
    ));
})->bind('public-films-detail');

$app->mount('/films', $filmsPublicControllers);

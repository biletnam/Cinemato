<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;

use model\Dao\Dao;

// Load controllers
require_once __DIR__.'/intranet/intranet.php';
require_once __DIR__.'/public/public.php';

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->find(1);

    $genreDao = Dao::getInstance()->getGenreDAO();
    $genre = $genreDao->find('test genre');

    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeur = $distributeurDao->find(1);

    echo '<pre>';
    return new Response(print_r(array($film, $genre, $distributeur), true));
});

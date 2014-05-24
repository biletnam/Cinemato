<?php

namespace controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

use model\Dao\Dao;

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->find(1);

    return new JsonResponse($film, 200);
});

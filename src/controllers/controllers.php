<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use model\Dao\Dao;

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->find(1);

    return new Response(print_r($film,true));
});

$app->get('/films', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->findAll();

    return new JsonResponse($films, 200);
});

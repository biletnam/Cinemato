<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

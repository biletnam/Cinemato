<?php

namespace controllers;

use model\DAO\DAO;

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    return DAO::f1TestDAO();
});

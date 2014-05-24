<?php

namespace controllers;

use model\Dao\Dao;

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    return Dao::f1TestDAO();
});

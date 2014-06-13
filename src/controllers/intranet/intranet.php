<?php

// Load controllers
require_once __DIR__.'/genres.php';
require_once __DIR__.'/distributeurs.php';
require_once __DIR__.'/films.php';
require_once __DIR__.'/statistiques.php';

$app->get('/intranet', function () use ($app) {
    return $app['twig']->render('pages/intranet/home.html.twig');
})->bind('intranet');

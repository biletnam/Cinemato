<?php

use model\Dao\Dao;
use model\Entite\Seance;



// Load controllers
require_once __DIR__.'/films.php';
require_once __DIR__.'/notation.php';

$app->get('/public', function () use ($app) {
	
	$seanceDao = Dao::getInstance()->getSeanceDAO();
	$seances = $seanceDao->findSeancesOfTheWeek(1);

	return $app['twig']->render('pages/public/seances/home.html.twig', array(
			'entities' => $seances
	));
})->bind('public');

<?php

use model\Dao\Dao;
use model\Entite\Film;



// Load controllers
require_once __DIR__.'/films.php';

$app->get('/public', function () use ($app) {
	$seanceDao = Dao::getInstance()->getSeanceDAO();
	
	$seances = $seanceDao->findSeancesOfTheWeek(1);
	
	return $app['twig']->render('pages/public/home.html.twig', array(
			'seances' => $seances
	));
})->bind('public');

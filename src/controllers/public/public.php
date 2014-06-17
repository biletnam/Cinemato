<?php

use model\Dao\Dao;
use model\Entite\Seance;

// Load controllers
require_once __DIR__.'/films.php';
require_once __DIR__.'/notation.php';

$app->get('/public', function () use ($app) {

	
	$seanceDao = Dao::getInstance()->getSeanceDAO();
	try{
		$seances = $seanceDao->findSeancesOfTheWeek(1);
	}catch (exception $e)
	{
		$app['session']->getFlashBag()->add('error', $e->getMessage());
		return $app['twig']->render('pages/home.html.twig');
	}
    return $app['twig']->render('pages/public/seances/home.html.twig', array(
            'entities' => $seances
    ));
})->bind('public');

$app->get('/about', function () use ($app) {
    return $app['twig']->render('pages/about.html.twig');
})->bind('about');

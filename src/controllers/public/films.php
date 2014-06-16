<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Film;

$filmsPublicControllers = $app['controllers_factory'];

$filmsPublicControllers->get('/', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();

    $films = $filmDao->findAll();

    return $app['twig']->render('pages/public/films/list.html.twig', array(
        'entities' => $films
    ));
})->bind('public-films-list');

$filmsPublicControllers->get('detail/{id}', function ($id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    return $app['twig']->render('pages/public/films/detail.html.twig', array(
        'entity' => $film
    ));
})->bind('public-films-detail');

$filmsPublicControllers->get('seance/{id}', function ($id) use ($app) {
	$filmDao = Dao::getInstance()->getFilmDAO();
	$film = $filmDao->find($id);

	$seanceDao = Dao::getInstance()->getSeanceDao();
	$seances = $seanceDao->findByFilmAndWeek($film,1);
	$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,2));
	$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,3));
	$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,4));
	
	return $app['twig']->render('pages/public/films/seances.html.twig', array(
			'entities' => $seances, 'film'=>$film
	));
	
})->bind('public-films-seances');


$app->mount('/films', $filmsPublicControllers);

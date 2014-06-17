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

    try{
    	$film = $filmDao->find($id);
    }catch (exception $e)
    {
    	$app['session']->getFlashBag()->add('error', $e->getMessage());
    	return $app['twig']->render('pages/home.html.twig');
    }
    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    return $app['twig']->render('pages/public/films/detail.html.twig', array(
        'entity' => $film
    ));
})->bind('public-films-detail');

$filmsPublicControllers->get('seance/{id}', function ($id) use ($app) {
	$filmDao = Dao::getInstance()->getFilmDAO();
	$seanceDao = Dao::getInstance()->getSeanceDao();

	try{
		$film = $filmDao->find($id);
		$seances = $seanceDao->findByFilmAndWeek($film,1);
		$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,2));
		$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,3));
		$seances = array_merge($seances, $seanceDao->findByFilmAndWeek($film,4));
	}catch (exception $e)
	{
		$app['session']->getFlashBag()->add('error', $e->getMessage());
		return $app['twig']->render('pages/home.html.twig');
	}

	return $app['twig']->render('pages/public/films/seances.html.twig', array(
			'entities' => $seances, 'film'=>$film
	));

})->bind('public-films-seances');


$app->mount('/public/films', $filmsPublicControllers);

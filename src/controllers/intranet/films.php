<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Film;
use forms\FilmForm;

$filmsControllers = $app['controllers_factory'];

$filmsControllers->get('/', function () use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $films = $filmDao->findAll();

    return $app['twig']->render('pages/intranet/films/list.html.twig', array(
        'entities' => $films
    ));
})->bind('intranet-films-list');

$filmsControllers->get('/new', function () use ($app) {
    $genreDao = Dao::getInstance()->getGenreDAO();
    $genres = $genreDao->findAll();

    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeurs = $distributeurDao->findAll();

    $form = $app['form.factory']->create(new FilmForm($genres, $distributeurs));

    return $app['twig']->render('pages/intranet/films/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-films-new');

$filmsControllers->post('/create', function (Request $request) use ($app) {
    $genreDao = Dao::getInstance()->getGenreDAO();
    $genres = $genreDao->findAll();

    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeurs = $distributeurDao->findAll();

    $form = $app['form.factory']->create(new FilmForm($genres, $distributeurs));

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $genre = $genreDao->find($data['genre']);
            $distributeur = $distributeurDao->find((int) $data['distributeur']);

            $film = new Film();
            $film->setTitre($data['titre']);
            $film->setDateDeSortie($data['dateDeSortie']);
            $film->setAgeMinimum($data['ageMinimum']);
            $film->setGenre($genre);
            $film->setDistributeur($distributeur);

            $filmDao = Dao::getInstance()->getFilmDao();

            if ($filmDao->create($film)) {
                $app['session']->getFlashBag()->add('success', 'Le film est bien enregistré !');

                return $app->redirect('/intranet/films');
            } else {
                $app['session']->getFlashBag()->add('error', 'Le film n\'a pas pu être enregistré.');
            }
        }
    }

    return $app['twig']->render('pages/intranet/films/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-films-create');

$filmsControllers->get('/{id}', function ($id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDAO();
    $films = $filmDao->find($id);

    return $app['twig']->render('pages/intranet/films/detail.html.twig', array(
        'film' => $film
    ));
})->bind('intranet-films-detail');

$app->mount('/intranet/films', $filmsControllers);

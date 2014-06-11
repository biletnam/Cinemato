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

                return $app->redirect($app['url_generator']->generate('intranet-films-list'));
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
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/films/detail.html.twig', array(
        'entity' => $film,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-films-detail');

$filmsControllers->get('/{id}/edit', function ($id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDao();
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    $genreDao = Dao::getInstance()->getGenreDAO();
    $genres = $genreDao->findAll();

    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeurs = $distributeurDao->findAll();

    $dateDeSortie = new DateTime($film->getDateDeSortie());

    $form = $app['form.factory']->create(new FilmForm($genres, $distributeurs), array(
        'titre' => $film->getTitre(),
        'dateDeSortie' => $dateDeSortie,
        'ageMinimum' => $film->getAgeMinimum(),
        'genre' => $film->getGenre()->getNom(),
        'distributeur' => $film->getDistributeur()->getId(),
    ));

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/films/edit.html.twig', array(
        'entity' => $film,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-films-edit');

$filmsControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDao();
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    $genreDao = Dao::getInstance()->getGenreDAO();
    $genres = $genreDao->findAll();

    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeurs = $distributeurDao->findAll();

    $form = $app['form.factory']->create(new FilmForm($genres, $distributeurs), array(
        'titre' => $film->getTitre(),
        'dateDeSortie' => $film->getDateDeSortie(),
        'ageMinimum' => $film->getAgeMinimum(),
        'genre' => $film->getGenre()->getNom(),
        'distributeur' => $film->getDistributeur()->getId(),
    ));

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $genre = $genreDao->find($data['genre']);
            $distributeur = $distributeurDao->find((int) $data['distributeur']);

            $film->setTitre($data['titre']);
            $film->setDateDeSortie($data['dateDeSortie']);
            $film->setAgeMinimum($data['ageMinimum']);
            $film->setGenre($genre);
            $film->setDistributeur($distributeur);

            if ($filmDao->update($film)) {
                $app['session']->getFlashBag()->add('success', 'Le film est bien mis à jour !');

                return $app->redirect('/intranet/films');
            } else {
                $app['session']->getFlashBag()->add('error', 'Le film n\'a pas pu être mis à jour.');
            }
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/films/edit.html.twig', array(
        'entity' => $film,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-films-update');

$filmsControllers->post('/{id}/delete', function (Request $request, $id) use ($app) {
    $filmDao = Dao::getInstance()->getFilmDao();
    $film = $filmDao->find($id);

    if (!$film) {
        $app->abort(404, 'Ce film n\'existe pas...');
    }

    if ($filmDao->delete($film)) {
        $app['session']->getFlashBag()->add('success', 'Le film a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'Le film n\'a pas pu être supprimé...');
    }

    return $app->redirect('/intranet/films');
})->bind('intranet-films-delete');

$app->mount('/intranet/films', $filmsControllers);

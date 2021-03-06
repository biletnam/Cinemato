<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Genre;
use forms\GenreForm;

$genresControllers = $app['controllers_factory'];

$genresControllers->get('/', function () use ($app) {
    $genreDao = Dao::getInstance()->getGenreDAO();
    try {
        $genres = $genreDao->findAll();
    } catch (Exception $e) {
        $app['session']->getFlashBag()->add('error', 'Vous avez généré une excection SQL, réassayez.');
    }
    

    return $app['twig']->render('pages/intranet/genres/list.html.twig', array(
        'entities' => $genres
    ));
})->bind('intranet-genres-list');

$genresControllers->get('/new', function () use ($app) {
    $form = $app['form.factory']->create(new GenreForm());

    return $app['twig']->render('pages/intranet/genres/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-genres-new');

$genresControllers->post('/create', function (Request $request) use ($app) {
    $form = $app['form.factory']->create(new GenreForm());

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $genre = new Genre();
            $genre->setNom($data['nom']);

            $genreDao = Dao::getInstance()->getGenreDao();
            try {
            if ($genreDao->create($genre)) {
                $app['session']->getFlashBag()->add('success', 'Le genre est bien enregistré !');

                return $app->redirect($app['url_generator']->generate('intranet-genres-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le genre n\'a pas pu être enregistré.');
            }
            } catch (Exception $e) {
                $app['session']->getFlashBag()->add('error', 'Le genre n\'a pas pu être enregistré.');
            }
        }
    }

    return $app['twig']->render('pages/intranet/genres/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-genres-create');

$genresControllers->get('/{id}', function ($id) use ($app) {
    $genreDao = Dao::getInstance()->getGenreDAO();
    
    try {
        $genre = $genreDao->find($id);
    } catch (Exception $e) {
        $app->abort(404, 'Ce genre n\'existe pas...');
    }

    if (!$genre) {
        $app->abort(404, 'Ce genre n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/genres/detail.html.twig', array(
        'entity' => $genre,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-genres-detail');

$genresControllers->post('/{id}/delete', function (Request $request, $id) use ($app) {
    $genreDao = Dao::getInstance()->getGenreDao();
    try {
        $genre = $genreDao->find($id);
    } catch (Exception $e) {
        $app->abort(404, 'Ce genre n\'existe pas...');
    }

    if (!$genre) {
        $app->abort(404, 'Ce genre n\'existe pas...');
    }

    if ($genreDao->delete($genre)) {
        $app['session']->getFlashBag()->add('success', 'Le genre a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'Le genre n\'a pas pu être supprimé...');
    }

    return $app->redirect($app['url_generator']->generate('intranet-genres-list'));
})->bind('intranet-genres-delete');

$app->mount('/intranet/genres', $genresControllers);

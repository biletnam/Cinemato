<?php

use Symfony\Component\HttpFoundation\Request;

use \DateTime;

use model\Dao\Dao;
use model\Entite\Seance;
use forms\SeanceForm;

$seancesControllers = $app['controllers_factory'];

$seancesControllers->get('/', function () use ($app) {
    $seanceDao = Dao::getInstance()->getSeanceDAO();
    try {
        $seances = $seanceDao->findAll();
    } catch (Exception $e) {
        $app['session']->getFlashBag()->add('error', 'Vous avez généré une excection SQL, réassayez.');
    }
    return $app['twig']->render('pages/intranet/seances/list.html.twig', array(
        'entities' => $seances
    ));
})->bind('intranet-seances-list');

$seancesControllers->get('/new', function () use ($app) {
    $sallesDao = Dao::getInstance()->getSalleDAO();
    $salles = $sallesDao->findAll();

    $filmsDao = Dao::getInstance()->getFilmDAO();
    $films = $filmsDao->findAll();

    $form = $app['form.factory']->create(new SeanceForm($salles, $films));

    return $app['twig']->render('pages/intranet/seances/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-seances-new');

$seancesControllers->post('/create', function (Request $request) use ($app) {
    try{
        $sallesDao = Dao::getInstance()->getSalleDAO();
        $salles = $sallesDao->findAll();

        $filmsDao = Dao::getInstance()->getFilmDAO();
        $films = $filmsDao->findAll();

        $form = $app['form.factory']->create(new SeanceForm($salles, $films));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $seance = new Seance();

            // Fix form mapping error
            $dateSeance = null;
            if (array_key_exists('dateSeance', $data)) {
                $dateSeance = $data['dateSeance'];
            } elseif ($request->request->has('seance_form')) {
                $requestData = $request->request->get('seance_form');
                $dateSeance = new DateTime($requestData['dateSeance']);
            } else {
                $app['session']->getFlashBag()->add('error', 'Les horaires sont indéfinies...');
            }

            $film = $filmsDao->find($data['film']);

            if ($film) {
                $seance->setFilm($film);
            } else {
                $app['session']->getFlashBag('error', 'Ce film n\'existe pas...');
            }

            $salle = $sallesDao->find($data['salle']);

            if ($salle) {
                $seance->setSalle($salle);
            } else {
                $app['session']->getFlashBag('error', 'Cette salle n\'existe pas...');
            }

            $seance->setDoublage($data['doublage']);

            $seancesDao = Dao::getInstance()->getSeanceDAO();

            if ($dateSeance && $film && $salle) {
                $seance->setDateSeance($dateSeance);
                if ($seancesDao->create($seance)) {
                    $app['session']->getFlashBag()->add('success', 'La séance a bien été programmée !');

                    return $app->redirect($app['url_generator']->generate('intranet-seances-list'));
                } else {
                    $app['session']->getFlashBag('error', 'La séance n\'a pas pu être programmée...');
                }
            } else {
                $app['session']->getFlashBag('error', 'La séance n\'a pas pu être programmée...');
            }
        }
    } catch (Exception $e){
            $app['session']->getFlashBag('error', 'La séance n\'a pas pu être programmée...');
    }

    return $app['twig']->render('pages/intranet/seances/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-seances-create');

$seancesControllers->get('/{id}', function ($id) use ($app) {
    try{
        $seancesDao = Dao::getInstance()->getSeanceDAO();
        $seance = $seancesDao->find($id);
    } catch (Exception $e){
        $app->abort(404, 'Cette séance n\'existe pas...');
    }
    if (!$seance) {
        $app->abort(404, 'Cette séance n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/seances/detail.html.twig', array(
        'entity' => $seance,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-seances-detail');

$seancesControllers->get('/{id}/edit', function ($id) use ($app) {
    try{
        $seancesDao = Dao::getInstance()->getSeanceDAO();
        $seance = $seancesDao->find($id);
    } catch (Exception $e){
        $app->abort(404, 'Cette séance n\'existe pas...');
    }
    try{
        if (!$seance) {
            $app->abort(404, 'Cette séance n\'existe pas...');
        }
        $sallesDao = Dao::getInstance()->getSalleDAO();
        $salles = $sallesDao->findAll();

        $filmsDao = Dao::getInstance()->getFilmDAO();
        $films = $filmsDao->findAll();

        $form = $app['form.factory']->create(new SeanceForm($salles, $films), array(
            'dateSeance' => $seance->getDateSeance(),
            'film' => $seance->getFilm()->getId(),
            'salle' => $seance->getSalle()->getNom(),
            'doublage' => $seance->getDoublage()
        ));

        $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();

        return $app['twig']->render('pages/intranet/seances/edit.html.twig', array(
            'entity' => $seance,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    } catch (Exception $e){
        $app->abort(404, 'Soucis de connexion avec la base...');
    }

})->bind('intranet-seances-edit');

$seancesControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    try{
        $seancesDao = Dao::getInstance()->getSeanceDAO();
        $seance = $seancesDao->find($id);

        if (!$seance) {
            $app->abort(404, 'Cette séance n\'existe pas...');
        }

        $sallesDao = Dao::getInstance()->getSalleDAO();
        $salles = $sallesDao->findAll();

        $filmsDao = Dao::getInstance()->getFilmDAO();
        $films = $filmsDao->findAll();

        $form = $app['form.factory']->create(new SeanceForm($salles, $films), array(
            'dateSeance' => $seance->getDateSeance(),
            'film' => $seance->getFilm()->getId(),
            'salle' => $seance->getSalle()->getNom(),
            'doublage' => $seance->getDoublage()
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $seance->setDateSeance($data['dateSeance']);

            $film = $filmsDao->find($data['film']);

            if ($film) {
                $seance->setFilm($film);
            } else {
                $app['session']->getFlashBag('error', 'Ce film n\'existe pas...');
            }

            $salle = $sallesDao->find($data['salle']);

            if ($salle) {
                $seance->setSalle($salle);
            } else {
                $app['session']->getFlashBag('error', 'Cette salle n\'existe pas...');
            }

            $seance->setDoublage($data['doublage']);

            $seancesDao = Dao::getInstance()->getSeanceDAO();

            if ($film && $salle) {
                if ($seancesDao->update($seance)) {
                    $app['session']->getFlashBag()->add('success', 'La séance a bien été bien mise à jour !');

                    return $app->redirect($app['url_generator']->generate('intranet-seances-list'));
                } else {
                    $app['session']->getFlashBag('error', 'La séance n\'a pas pu être mise à jour...');
                }
            }
        }
    }
    catch (Exception $e) {
       $app['session']->getFlashBag('error', 'La séance n\'a pas pu être mise à jour...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/seances/edit.html.twig', array(
        'entity' => $seance,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-seances-update');

$seancesControllers->post('/{id}/delete', function ($id) use ($app) {
    $seancesDao = Dao::getInstance()->getSeanceDAO();
    try {
        $seance = $seancesDao->find($id);
    } catch (Exception $e) {
        $app->abort(404, 'Cette séance n\'existe pas...');
    }


    if (!$seance) {
        $app->abort(404, 'Cette séance n\'existe pas...');
    }
    try{
        if ($seancesDao->delete($seance)) {
            $app['session']->getFlashBag('success', 'La séance a bien été supprimée !');

            return $app->redirect($app['url_generator']->generate('intranet-seances-list'));
        } else {
            $app['session']->getFlashBag('error', 'La séance n\'a pas pu être supprimée...');
        }
    } catch (Exception $e){
        $app['session']->getFlashBag('error', 'La séance n\'a pas pu être supprimée...');
    }
    return $app['twig']->render('pages/intranet/seances/detail.html.twig', array(
        'entity' => $seance
    ));
})->bind('intranet-seances-delete');

$app->mount('/intranet/seances', $seancesControllers);

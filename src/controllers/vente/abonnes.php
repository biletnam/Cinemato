<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\PersonneAbonne;
use forms\PersonneAbonneForm;

$abonnesControllers = $app['controllers_factory'];

$abonnesControllers->get('/', function () use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDAO();
    $abonnes = $personneDao->findAllAbonnes();

    return $app['twig']->render('pages/vente/abonnes/list.html.twig', array(
        'entities' => $abonnes
    ));
})->bind('vente-abonnes-list');

$abonnesControllers->get('/new', function () use ($app) {
    $abonne = new PersonneAbonne();
    $form = $app['form.factory']->create(new PersonneAbonneForm(), $abonne);

    return $app['twig']->render('pages/vente/abonnes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-abonnes-new');

$abonnesControllers->post('/create', function (Request $request) use ($app) {
    $abonne = new PersonneAbonne();
    $form = $app['form.factory']->create(new PersonneAbonneForm(), $abonne);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $personneDao = Dao::getInstance()->getPersonneDao();

            if ($personneDao->create($abonne)) {
                $app['session']->getFlashBag()->add('success', 'L\'abonné est bien enregistré !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a pas pu être enregistré.');
            }
        }
    }

    return $app['twig']->render('pages/vente/abonnes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-abonnes-create');

$abonnesControllers->get('/{id}', function ($id) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce abonne n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/detail.html.twig', array(
        'entity' => $abonne,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-abonnes-detail');

$abonnesControllers->get('/{id}/edit', function ($id) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce abonne n\'existe pas...');
    }

    $form = $app['form.factory']->create(new PersonneAbonneForm(), $abonne);

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/edit.html.twig', array(
        'entity' => $abonne,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-abonnes-edit');

$abonnesControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce abonne n\'existe pas...');
    }

    $form = $app['form.factory']->create(new PersonneAbonneForm(), $abonne);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($personneDao->update($abonne)) {
                $app['session']->getFlashBag()->add('success', 'L\'abonné est bien mis à jour !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a pas pu être mis à jour.');
            }
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/edit.html.twig', array(
        'entity' => $abonne,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-abonnes-update');

$abonnesControllers->post('/{id}/delete', function (Request $request, $id) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce abonne n\'existe pas...');
    }

    if ($personneDao->delete($abonne)) {
        $app['session']->getFlashBag()->add('success', 'L\'abonné a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a pas pu être supprimé...');
    }

    return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
})->bind('vente-abonnes-delete');

$app->mount('/vente/abonnes', $abonnesControllers);

<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\PersonneAbonne;
use forms\PersonneAbonneForm;
use model\Entite\Rechargement;
use forms\RechargementForm;

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
                $app['session']->getFlashBag()->add('success', 'Le compte abonné est bien enregistré !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le compte abonné n\'a pas pu être enregistré.');
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
                $app['session']->getFlashBag()->add('success', 'Le compte abonné est bien mis à jour !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le compte abonné n\'a pas pu être mis à jour.');
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
        $app->abort(404, 'Ce compte abonné n\'existe pas...');
    }

    if ($personneDao->delete($abonne)) {
        $app['session']->getFlashBag()->add('success', 'Le compte abonné a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'Le compte abonné n\'a pas pu être supprimé...');
    }

    return $app->redirect($app['url_generator']->generate('vente-abonnes-list'));
})->bind('vente-abonnes-delete');

$abonnesControllers->match('/{id}/refill', function (Request $request, $id) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce compte abonné n\'existe pas...');
    }

    $rechargement = new Rechargement();
    $form = $app['form.factory']->create(new RechargementForm(), $rechargement);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $rechargementsDao = Dao::getInstance()->getRechargementDAO();

            if ($rechargementsDao->create($rechargement, $abonne)) {
                $app['session']->getFlashBag()->add('success', 'Le compte abonné a bien été rechargé !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-detail', array('id' => $abonne->getId())));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le compte abonné n\'a pas pu être rechargé...');
            }
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/refill/new.html.twig', array(
        'entity' => $abonne,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->method('GET|POST')->bind('vente-abonnes-refill');

$abonnesControllers->get('/{id}/refill/{refillId}/edit', function (Request $request, $id, $refillId) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce compte abonné n\'existe pas...');
    }

    $rechargementsDao = Dao::getInstance()->getRechargementDAO();

    $rechargement = $rechargementsDao->find($refillId);

    if (!$rechargement) {
        $app->abort(404, 'Cette recharge n\'existe pas...');
    }

    $form = $app['form.factory']->create(new RechargementForm(), $rechargement);

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/refill/edit.html.twig', array(
        'entity' => $abonne,
        'refill' => $rechargement,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-abonnes-refill-edit');

$abonnesControllers->post('/{id}/refill/{refillId}/update', function (Request $request, $id, $refillId) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce compte abonné n\'existe pas...');
    }

    $rechargementsDao = Dao::getInstance()->getRechargementDAO();

    $rechargement = $rechargementsDao->find($refillId);

    if (!$rechargement) {
        $app->abort(404, 'Cette recharge n\'existe pas...');
    }

    $form = $app['form.factory']->create(new RechargementForm(), $rechargement);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($rechargementsDao->update($rechargement)) {
                $app['session']->getFlashBag()->add('success', 'La recharge a bien été mise à jour !');

                return $app->redirect($app['url_generator']->generate('vente-abonnes-detail', array('id' => $abonne->getId())));
            } else {
                $app['session']->getFlashBag()->add('error', 'La recharge n\'a pas pu être mise à jour...');
            }
        }
    }
    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/abonnes/refill/edit.html.twig', array(
        'entity' => $abonne,
        'refill' => $rechargement,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-abonnes-refill-update');

$abonnesControllers->post('/{id}/refill/{refillId}/delete', function (Request $request, $id, $refillId) use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDao();
    $abonne = $personneDao->find($id);

    if (!$abonne) {
        $app->abort(404, 'Ce compte abonné n\'existe pas...');
    }

    $rechargementsDao = Dao::getInstance()->getRechargementDAO();

    $rechargement = $rechargementsDao->find($refillId);

    if (!$rechargement) {
        $app->abort(404, 'Cette recharge n\'existe pas...');
    }

    if ($rechargementsDao->delete($rechargement)) {
        $app['session']->getFlashBag()->add('success', 'La recharge a bien été supprimée !');
    } else {
        $app['session']->getFlashBag()->add('error', 'La recharge n\'a pas pu être supprimée...');
    }

    return $app->redirect($app['url_generator']->generate('vente-abonnes-detail', array('id' => $abonne->getId())));
})->bind('vente-abonnes-refill-delete');

$app->mount('/vente/abonnes', $abonnesControllers);

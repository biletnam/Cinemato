<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Distributeur;
use forms\DistributeurForm;

$distributeursControllers = $app['controllers_factory'];

$distributeursControllers->get('/', function () use ($app) {
    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeurs = $distributeurDao->findAll();

    return $app['twig']->render('pages/intranet/distributeurs/list.html.twig', array(
        'entities' => $distributeurs
    ));
})->bind('intranet-distributeurs-list');

$distributeursControllers->get('/new', function () use ($app) {
    $form = $app['form.factory']->create(new DistributeurForm());

    return $app['twig']->render('pages/intranet/distributeurs/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-distributeurs-new');

$distributeursControllers->post('/create', function (Request $request) use ($app) {
    $form = $app['form.factory']->create(new DistributeurForm());

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $distributeur = new Distributeur();
            $distributeur->setPrenom($data['prenom']);
            $distributeur->setNom($data['nom']);
            $distributeur->setAdresse($data['adresse']);
            $distributeur->setTelephone($data['telephone']);


            $distributeurDao = Dao::getInstance()->getDistributeurDao();

            if ($distributeurDao->create($distributeur)) {
                $app['session']->getFlashBag()->add('success', 'Le distributeur est bien enregistré !');

                return $app->redirect($app['url_generator']->generate('intranet-distributeurs-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le distributeur n\'a pas pu être enregistré.');
            }
        }
    }

    return $app['twig']->render('pages/intranet/distributeurs/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('intranet-distributeurs-create');

$distributeursControllers->get('/{id}', function ($id) use ($app) {
    $distributeurDao = Dao::getInstance()->getDistributeurDAO();
    $distributeur = $distributeurDao->find($id);

    if (!$distributeur) {
        $app->abort(404, 'Ce distributeur n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/distributeurs/detail.html.twig', array(
        'entity' => $distributeur,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-distributeurs-detail');

$distributeursControllers->get('/{id}/edit', function ($id) use ($app) {
    $distributeurDao = Dao::getInstance()->getDistributeurDao();
    $distributeur = $distributeurDao->find($id);

    if (!$distributeur) {
        $app->abort(404, 'Ce distributeur n\'existe pas...');
    }

    $form = $app['form.factory']->create(new DistributeurForm(), array(
        'prenom' => $distributeur->getPrenom(),
        'nom' => $distributeur->getNom(),
        'adresse' => $distributeur->getAdresse(),
        'telephone' => $distributeur->getTelephone()
    ));

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/distributeurs/edit.html.twig', array(
        'entity' => $distributeur,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-distributeurs-edit');

$distributeursControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $distributeurDao = Dao::getInstance()->getDistributeurDao();
    $distributeur = $distributeurDao->find($id);

    if (!$distributeur) {
        $app->abort(404, 'Ce distributeur n\'existe pas...');
    }

    $form = $app['form.factory']->create(new DistributeurForm(), array(
        'prenom' => $distributeur->getPrenom(),
        'nom' => $distributeur->getNom(),
        'adresse' => $distributeur->getAdresse(),
        'telephone' => $distributeur->getTelephone()
    ));

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $distributeur->setPrenom($data['prenom']);
            $distributeur->setNom($data['nom']);
            $distributeur->setAdresse($data['adresse']);
            $distributeur->setTelephone($data['telephone']);

            if ($distributeurDao->update($distributeur)) {
                $app['session']->getFlashBag()->add('success', 'Le distributeur est bien mis à jour !');

                return $app->redirect($app['url_generator']->generate('intranet-distributeurs-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le distributeur n\'a pas pu être mis à jour.');
            }
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/intranet/distributeurs/edit.html.twig', array(
        'entity' => $distributeur,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('intranet-distributeurs-update');

$distributeursControllers->post('/{id}/delete', function (Request $request, $id) use ($app) {
    $distributeurDao = Dao::getInstance()->getDistributeurDao();
    $distributeur = $distributeurDao->find($id);

    if (!$distributeur) {
        $app->abort(404, 'Ce distributeur n\'existe pas...');
    }

    if ($distributeurDao->delete($distributeur)) {
        $app['session']->getFlashBag()->add('success', 'Le distributeur a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'Le distributeur n\'a pas pu être supprimé...');
    }

    return $app->redirect($app['url_generator']->generate('intranet-distributeurs-list'));
})->bind('intranet-distributeurs-delete');

$app->mount('/intranet/distributeurs', $distributeursControllers);

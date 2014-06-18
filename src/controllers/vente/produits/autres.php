<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitAutre;
use forms\ProduitAutreForm;

$autresControllers = $app['controllers_factory'];

$autresControllers->get('/', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $autres = $produitsDao->findAllAutres();

    return $app['twig']->render('pages/vente/produits/autres/list.html.twig', array(
        'entities' => $autres
    ));
})->bind('vente-produit-autres-list');

$autresControllers->get('/new', function () use ($app) {
    $autre = new ProduitAutre();
    $form = $app['form.factory']->create(new ProduitAutreForm(), $autre);

    return $app['twig']->render('pages/vente/produits/autres/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-autres-new');

$autresControllers->post('/create', function (Request $request) use ($app) {
    $autre = new ProduitAutre();
    $form = $app['form.factory']->create(new ProduitAutreForm(), $autre);

    $form->handleRequest($request);

    if ($form->isValid()) {
        $autresDao = Dao::getInstance()->getProduitDAO();

        if ($autresDao->create($autre)) {
            $app['session']->getFlashBag()->add('success', 'Votre autre produit est bien enregistrée');

            return $app->redirect($app['url_generator']->generate('vente-produit-autres-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre autre produit n\'a pas pu être enregistrée');
        }
    }

    return $app['twig']->render('pages/vente/produits/autres/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-autres-create');

$autresControllers->get('/{id}', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $autre = $produitsDao->findAutre($id);

    if (!$autre) {
        $app->abort(404, 'Cette autre n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/autres/detail.html.twig', array(
        'entity' => $autre,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-autres-detail');

$autresControllers->get('/{id}/edit', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $autre = $produitsDao->findAutre($id);

    if (!$autre) {
        $app->abort(404, 'Cette autre n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitAutreForm(true), $autre);

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/autres/edit.html.twig', array(
        'entity' => $autre,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-autres-edit');

$autresControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $autre = $produitsDao->findAutre($id);

    if (!$autre) {
        $app->abort(404, 'Cette autre n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitAutreForm(true), $autre);

    $form->handleRequest($request);

    if ($form->isValid()) {
        if ($produitsDao->update($autre)) {
            $app['session']->getFlashBag()->add('success', 'Votre produit autre est bien mise à jour');

            return $app->redirect($app['url_generator']->generate('vente-produit-autres-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre produit autre n\'a pas pu être mise à jour');
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/autres/edit.html.twig', array(
        'entity' => $autre,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-autres-update');

$autresControllers->post('/{id}/delete', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $autre = $produitsDao->findAutre($id);

    if (!$autre) {
        $app->abort(404, 'Cette autre n\'existe pas...');
    }

    if ($produitsDao->delete($autre)) {
        $app['session']->getFlashBag()->add('success', 'L\'autre produit a bien été supprimée !');
    } else {
        $app['session']->getFlashBag()->add('error', 'L\'autre produit n\'a pas pu être supprimée...');
    }

    return $app->redirect($app['url_generator']->generate('vente-produit-autres-list'));
})->bind('vente-produit-autres-delete');

$app->mount('/vente/produits/autres', $autresControllers);

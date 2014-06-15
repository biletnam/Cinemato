<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitBoisson;
use forms\ProduitBoissonForm;

$boissonsControllers = $app['controllers_factory'];

$boissonsControllers->get('/', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $boissons = $produitsDao->findAllBoissons();

    return $app['twig']->render('pages/vente/produits/boissons/list.html.twig', array(
        'entities' => $boissons
    ));
})->bind('vente-produit-boissons-list');

$boissonsControllers->get('/new', function () use ($app) {
    $boisson = new ProduitBoisson();
    $form = $app['form.factory']->create(new ProduitBoissonForm(), $boisson);

    return $app['twig']->render('pages/vente/produits/boissons/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-boissons-new');

$boissonsControllers->post('/create', function (Request $request) use ($app) {
    $boisson = new ProduitBoisson();
    $form = $app['form.factory']->create(new ProduitBoissonForm(), $boisson);

    $form->handleRequest($request);

    if ($form->isValid()) {
        $boissonsDao = Dao::getInstance()->getProduitDAO();

        if ($boissonsDao->create($boisson)) {
            $app['session']->getFlashBag()->add('success', 'Votre boisson est bien enregistrée');

            return $app->redirect($app['url_generator']->generate('vente-produit-boissons-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre boisson n\'a pas pu être enregistrée');
        }
    }

    return $app['twig']->render('pages/vente/produits/boissons/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-boissons-create');

$boissonsControllers->get('/{id}', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $boisson = $produitsDao->findBoisson($id);

    if (!$boisson) {
        $app->abort(404, 'Cette boisson n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/boissons/detail.html.twig', array(
        'entity' => $boisson,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-boissons-detail');

$boissonsControllers->get('/{id}/edit', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $boisson = $produitsDao->findBoisson($id);

    if (!$boisson) {
        $app->abort(404, 'Cette boisson n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitBoissonForm(true), $boisson);

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/boissons/edit.html.twig', array(
        'entity' => $boisson,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-boissons-edit');

$boissonsControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $boisson = $produitsDao->findBoisson($id);

    if (!$boisson) {
        $app->abort(404, 'Cette boisson n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitBoissonForm(true), $boisson);

    $form->handleRequest($request);

    if ($form->isValid()) {
        if ($produitsDao->update($boisson)) {
            $app['session']->getFlashBag()->add('success', 'Votre boisson est bien mise à jour');

            return $app->redirect($app['url_generator']->generate('vente-produit-boissons-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre boisson n\'a pas pu être mise à jour');
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/boissons/edit.html.twig', array(
        'entity' => $boisson,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-boissons-update');

$boissonsControllers->post('/{id}/delete', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $boisson = $produitsDao->findBoisson($id);

    if (!$boisson) {
        $app->abort(404, 'Cette boisson n\'existe pas...');
    }

    if ($produitsDao->delete($boisson)) {
        $app['session']->getFlashBag()->add('success', 'La boisson a bien été supprimée !');
    } else {
        $app['session']->getFlashBag()->add('error', 'La boisson n\'a pas pu être supprimée...');
    }

    return $app->redirect($app['url_generator']->generate('vente-produit-boissons-list'));
})->bind('vente-produit-boissons-delete');

$app->mount('/vente/produits/boissons', $boissonsControllers);

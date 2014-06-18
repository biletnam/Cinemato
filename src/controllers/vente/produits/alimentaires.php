<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitAlimentaire;
use forms\ProduitAlimentaireForm;

$alimentairesControllers = $app['controllers_factory'];

$alimentairesControllers->get('/', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $alimentaires = $produitsDao->findAllAlimentaires();

    return $app['twig']->render('pages/vente/produits/alimentaires/list.html.twig', array(
        'entities' => $alimentaires
    ));
})->bind('vente-produit-alimentaires-list');

$alimentairesControllers->get('/new', function () use ($app) {
    $alimentaire = new ProduitAlimentaire();
    $form = $app['form.factory']->create(new ProduitAlimentaireForm(), $alimentaire);

    return $app['twig']->render('pages/vente/produits/alimentaires/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-alimentaires-new');

$alimentairesControllers->post('/create', function (Request $request) use ($app) {
    $alimentaire = new ProduitAlimentaire();
    $form = $app['form.factory']->create(new ProduitAlimentaireForm(), $alimentaire);

    $form->handleRequest($request);

    if ($form->isValid()) {
        $alimentairesDao = Dao::getInstance()->getProduitDAO();

        if ($alimentairesDao->create($alimentaire)) {
            $app['session']->getFlashBag()->add('success', 'Votre produit alimentaire est bien enregistrée');

            return $app->redirect($app['url_generator']->generate('vente-produit-alimentaires-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre produit alimentaire n\'a pas pu être enregistrée');
        }
    }

    return $app['twig']->render('pages/vente/produits/alimentaires/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-alimentaires-create');

$alimentairesControllers->get('/{id}', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $alimentaire = $produitsDao->findAlimentaire($id);

    if (!$alimentaire) {
        $app->abort(404, 'Ce produit alimentaire n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/alimentaires/detail.html.twig', array(
        'entity' => $alimentaire,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-alimentaires-detail');

$alimentairesControllers->get('/{id}/edit', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $alimentaire = $produitsDao->findAlimentaire($id);

    if (!$alimentaire) {
        $app->abort(404, 'Cette alimentaire n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitAlimentaireForm(true), $alimentaire);

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/alimentaires/edit.html.twig', array(
        'entity' => $alimentaire,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-alimentaires-edit');

$alimentairesControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $alimentaire = $produitsDao->findAlimentaire($id);

    if (!$alimentaire) {
        $app->abort(404, 'Ce produit alimentaire n\'existe pas...');
    }

    $form = $app['form.factory']->create(new ProduitAlimentaireForm(true), $alimentaire);

    $form->handleRequest($request);

    if ($form->isValid()) {
        if ($produitsDao->update($alimentaire)) {
            $app['session']->getFlashBag()->add('success', 'Votre produit alimentaire est bien mise à jour');

            return $app->redirect($app['url_generator']->generate('vente-produit-alimentaires-list'));
        } else {
            $app['session']->getFlashBag()->add('error', 'Votre produit alimentaire n\'a pas pu être mise à jour');
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/produits/alimentaires/edit.html.twig', array(
        'entity' => $alimentaire,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-produit-alimentaires-update');

$alimentairesControllers->post('/{id}/delete', function ($id) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDao();
    $alimentaire = $produitsDao->findAlimentaire($id);

    if (!$alimentaire) {
        $app->abort(404, 'Ce produit alimentaire n\'existe pas...');
    }

    if ($produitsDao->delete($alimentaire)) {
        $app['session']->getFlashBag()->add('success', 'Le produit alimentaire a bien été supprimée !');
    } else {
        $app['session']->getFlashBag()->add('error', 'Le produit alimentaire n\'a pas pu être supprimée...');
    }

    return $app->redirect($app['url_generator']->generate('vente-produit-alimentaires-list'));
})->bind('vente-produit-alimentaires-delete');

$app->mount('/vente/produits/alimentaires', $alimentairesControllers);

<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitVendeur;
use forms\ProduitVendeurForm;

$boissonsVentesControllers = $app['controllers_factory'];

$boissonsVentesControllers->get('/', function () use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();
    $produits = $produitsVendeurDao->findAllBoissons();

    return $app['twig']->render('pages/vente/produits/boissons/ventes/list.html.twig', array(
        'entities' => $produits
    ));
})->bind('vente-produit-boissons-ventes-list');

$boissonsVentesControllers->get('/new', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllBoissons();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    return $app['twig']->render('pages/vente/produits/boissons/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-boissons-ventes-new');

$boissonsVentesControllers->post('/create', function (Request $request) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllBoissons();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    $form->handleRequest($request);

    if ($form->isValid()) {
        $personnesDao = Dao::getInstance()->getPersonneDAO();
        $produitVendeur = new ProduitVendeur();

        $vendeur = $personnesDao->findFirstVendeur();
        $produitVendeur->setVendeur($vendeur);

        $data = $form->getData();
        try{
        	$produit = $produitsDao->findBoisson($data['produit']);
        }catch (exception $e)
        {
        	$app['session']->getFlashBag()->add('error', $e->getMessage());
        	return $app['twig']->render('pages/home.html.twig');
        }

        if ($produit) {
            $produitVendeur->setProduit($produit);
            $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();

            if ($produitsVendeurDao->create($produitVendeur)) {
                $app['session']->getFlashBag()->add('success', 'Votre vente de boisson a bien été enregistrée');

                return $app->redirect($app['url_generator']->generate('vente-produit-boissons-ventes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Votre vente de boisson n\'a pas pu être enregistrée');
            }
        } else {
            $app['session']->getFlashBag()->add('error', 'Ce produit n\'existe pas...');
        }
    }

    return $app['twig']->render('pages/vente/produits/boissons/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-boissons-ventes-create');

$boissonsVentesControllers->get('/{id}', function ($id) use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();
    try{
    	$produitVendeur = $produitsVendeurDao->find($id);
    }catch (exception $e)
    {
    	$app['session']->getFlashBag()->add('error', $e->getMessage());
    	return $app['twig']->render('pages/home.html.twig');
    }

    if (!$produitVendeur) {
        $app->abort(404, 'Cette vente de boisson n\'existe pas...');
    }

    return $app['twig']->render('pages/vente/produits/boissons/ventes/detail.html.twig', array(
        'entity' => $produitVendeur
    ));
})->bind('vente-produit-boissons-ventes-detail');

$app->mount('/vente/produits/boissons/ventes', $boissonsVentesControllers);

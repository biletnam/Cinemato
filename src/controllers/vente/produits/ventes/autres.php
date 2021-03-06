<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitVendeur;
use forms\ProduitVendeurForm;

$autresVentesControllers = $app['controllers_factory'];

$autresVentesControllers->get('/', function () use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();
    $produits = $produitsVendeurDao->findAllAutres();

    return $app['twig']->render('pages/vente/produits/autres/ventes/list.html.twig', array(
        'entities' => $produits
    ));
})->bind('vente-produit-autres-ventes-list');

$autresVentesControllers->get('/new', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllAutres();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    return $app['twig']->render('pages/vente/produits/autres/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-autres-ventes-new');

$autresVentesControllers->post('/create', function (Request $request) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllAutres();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    $form->handleRequest($request);

    if ($form->isValid()) {
        $personnesDao = Dao::getInstance()->getPersonneDAO();
        $produitVendeur = new ProduitVendeur();

        $vendeur = $personnesDao->findFirstVendeur();
        $produitVendeur->setVendeur($vendeur);

        $data = $form->getData();

        $produit = $produitsDao->findAutre($data['produit']);

        if ($produit) {
            $produitVendeur->setProduit($produit);
            $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();

            if ($produitsVendeurDao->create($produitVendeur)) {
                $app['session']->getFlashBag()->add('success', 'Votre vente d\'autre produit a bien été enregistrée');

                return $app->redirect($app['url_generator']->generate('vente-produit-autres-ventes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Votre vente d\'autre produit n\'a pas pu être enregistrée');
            }
        } else {
            $app['session']->getFlashBag()->add('error', 'Ce produit n\'existe pas...');
        }
    }

    return $app['twig']->render('pages/vente/produits/autres/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-autres-ventes-create');

$autresVentesControllers->get('/{id}', function ($id) use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();

    try{
    	$produitVendeur = $produitsVendeurDao->find($id);
    }catch (exception $e)
    {
    	$app['session']->getFlashBag()->add('error', $e->getMessage());
    	return $app['twig']->render('pages/home.html.twig');
    }

    if (!$produitVendeur) {
        $app->abort(404, 'Cette vente d\'autre produit n\'existe pas...');
    }

    return $app['twig']->render('pages/vente/produits/autres/ventes/detail.html.twig', array(
        'entity' => $produitVendeur
    ));
})->bind('vente-produit-autres-ventes-detail');

$app->mount('/vente/produits/autres/ventes', $autresVentesControllers);

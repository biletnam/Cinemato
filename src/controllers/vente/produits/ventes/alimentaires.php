<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\ProduitVendeur;
use forms\ProduitVendeurForm;

$alimentairesVentesControllers = $app['controllers_factory'];

$alimentairesVentesControllers->get('/', function () use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();
    $produits = $produitsVendeurDao->findAllAlimentaires();

    return $app['twig']->render('pages/vente/produits/alimentaires/ventes/list.html.twig', array(
        'entities' => $produits
    ));
})->bind('vente-produit-alimentaires-ventes-list');

$alimentairesVentesControllers->get('/new', function () use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllAlimentaires();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    return $app['twig']->render('pages/vente/produits/alimentaires/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-alimentaires-ventes-new');

$alimentairesVentesControllers->post('/create', function (Request $request) use ($app) {
    $produitsDao = Dao::getInstance()->getProduitDAO();
    $produits = $produitsDao->findAllAlimentaires();

    $form = $app['form.factory']->create(new ProduitVendeurForm($produits));

    $form->handleRequest($request);

    if ($form->isValid()) {
        $personnesDao = Dao::getInstance()->getPersonneDAO();
        $produitVendeur = new ProduitVendeur();

        $vendeur = $personnesDao->findFirstVendeur();
        $produitVendeur->setVendeur($vendeur);

        $data = $form->getData();
        try{
        	$produit = $produitsDao->findAlimentaire($data['produit']);
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

                return $app->redirect($app['url_generator']->generate('vente-produit-alimentaires-ventes-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Votre vente de boisson n\'a pas pu être enregistrée');
            }
        } else {
            $app['session']->getFlashBag()->add('error', 'Ce produit n\'existe pas...');
        }
    }

    return $app['twig']->render('pages/vente/produits/alimentaires/ventes/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-produit-alimentaires-ventes-create');

$alimentairesVentesControllers->get('/{id}', function ($id) use ($app) {
    $produitsVendeurDao = Dao::getInstance()->getProduitVendeurDao();

    try{
    	$produitVendeur = $produitsVendeurDao->find($id);
    }
    catch (exception $e)
    {
    	$app['session']->getFlashBag()->add('error', $e->getMessage());
    	return $app['twig']->render('pages/home.html.twig');
    }

    if (!$produitVendeur) {
        $app->abort(404, 'Cette vente de produit alimentaire n\'existe pas...');
    }

    return $app['twig']->render('pages/vente/produits/alimentaires/ventes/detail.html.twig', array(
        'entity' => $produitVendeur
    ));
})->bind('vente-produit-alimentaires-ventes-detail');

$app->mount('/vente/produits/alimentaires/ventes', $alimentairesVentesControllers);

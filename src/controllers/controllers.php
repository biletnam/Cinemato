<?php

namespace controllers;

use Symfony\Component\HttpFoundation\Response;

use model\Dao\Dao;
use model\Entite\Tarif;
use model\Entite\Salle;
use model\Entite\ProduitAlimentaire;
use model\Entite\ProduitAutre;
use model\Entite\ProduitBoissons;
use model\Dao\TicketDAO;
use model\Entite\ProduitVendeur;

// Load controllers
require_once __DIR__.'/intranet/intranet.php';
require_once __DIR__.'/vente/vente.php';
require_once __DIR__.'/public/public.php';

$app->get('/', function() use ($app) {
    return $app['twig']->render('pages/home.html.twig');
})->bind('home');

$app->get('/DAO/test', function () use ($app) {
    $seanceDao = Dao::getInstance()->getSeanceDAO();
    //$salleDao = Dao::getInstance()->getSalleDAO();
    //$salle = $salleDao->find('Salle Zinédine Zidane');
    //$seance = $seanceDao->find(new \DateTime('2014-06-25 14:00:00'),$salle);
    //$seance->setDateSeance(new \DateTime('2014-06-25 14:00:00'));
    //$seance->setDoublage('LOLESQUE');
    //$seanceDao->findSeancesOfTheWeek(0);
    //$seanceDao->create($seance);
    /*$ticketDao = Dao::getInstance()->getTicketDao();
    $ticket = $ticketDao->find(9);
    $ticket->setNote(20);
    $ticketDao->delete($ticket);*/
    /*$produitVendeurDao = Dao::getInstance()->getProduitVendeurDao();
    $produitDao = Dao::getInstance()->getProduitDAO();
    $vendeurDao = Dao::getInstance()->getPersonneDAO();
    
    $produit = $produitDao->find(663);
    $vendeur = $vendeurDao->find(4);
    $date = new \DateTime("now");
    
    $produitVendeur = $produitVendeurDao->findAllByVendeur($vendeur);*/
    //$produitVendeur->setDate($date);
    //$produitVendeur->setVendeur($vendeur);
    //$produitVendeur->setProduit($produit);
    //$produitVendeurDao->delete($produitVendeur);
    
    //$produitVente = $produitVendeurDao->findAll();
    $filmDao = Dao::getInstance()->getFilmDAO();
    $films = $filmDao->findFilmSemaine(1);
    echo '<pre>';
    return new Response(var_dump($films));
});

<?php
use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Distributeur;
use forms\DistributeurForm;

$distributeursControllers = $app['controllers_factory'];

$distributeursControllers->get('/', function (Request $request, $offset=0) use ($app) {
    $offset = 0;
    $subRequest = Request::create('/intranet/statistiques/'.$offset);
    $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    return $response;
})->bind('intranet-statistiques-list');

$distributeursControllers->get('/{offset}', function (Request $request, $offset=0) use ($app) {
    $nbAbonne = Dao::getInstance()->getStatistiquesDao()->getNbAbonne();
    $nbFilm = Dao::getInstance()->getStatistiquesDao()->getNbFilmSemaine(0);
    
    
    $dateDebut = new DateTime('now');
    $offsetPourMercredi = date('N',$dateDebut->getTimestamp());
    if($offsetPourMercredi > 0){
        $dateInterval = new DateInterval('P'.$offsetPourMercredi.'D');
        $dateDebut->sub($dateInterval);
    }
        
    else{
        $dateInterval = new DateInterval('P'.-$offsetPourMercredi.'D');
        $dateDebut->add($dateInterval);
    }
    $dateFin = new DateTime($dateDebut->format('Y-m-d H:i:s'));
    $dateInterval2 = new DateInterval("P7D");
    $dateFin->add($dateInterval2);
    
    $tabFilmEtSeance = array();
    $tabFilmsSemaine = Dao::getInstance()->getFilmDAO()->findFilmSemaine(-$offset);
    foreach ($tabFilmsSemaine as $i => $film){
        $tabSeance = Dao::getInstance()->getSeanceDAO()->findByFilm($film);
        $entre = Dao::getInstance()->getStatistiquesDao()->getTotalEntreFilm($film);
        $revenue = Dao::getInstance()->getStatistiquesDao()->getTotalRevenueFilm($film);
        $tabFilmEtSeance[$i] = array(
            'film' => $film,
            'seance' =>$tabSeance,
            'entre' => $entre,
            'revenue'=>$revenue
            );
    }
    //exit(var_dump($tabFilmEtSeance));
    
    return $app['twig']->render('pages/intranet/statistiques/list.html.twig', array(
        'nbAbonne' => $nbAbonne,
        'nbFilm' => $nbFilm,
        'dateDebutSemaine' => $dateDebut,
        'dateFinSemaine' => $dateFin,
        'offset' => $offset,
        'tabFilmSeance' => $tabFilmEtSeance
    ));
})->bind('intranet-statistiques-list-offset');

$app->mount('/intranet/statistiques', $distributeursControllers);

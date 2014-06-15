<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use model\Dao\Dao;
use model\Entite\Distributeur;
use forms\DistributeurForm;

$distributeursControllers = $app['controllers_factory'];

$distributeursControllers->get('/', function (Request $request, $offset=0) use ($app) {
    $offset = 0;
    $subRequest = Request::create('/intranet/statistiques/0', 'GET');

    return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
})->bind('intranet-statistiques-list');

$distributeursControllers->get('/{offset}', function (Request $request, $offset=0) use ($app) {
    $nbAbonne = Dao::getInstance()->getStatistiquesDao()->getNbAbonne();
    $nbFilm = Dao::getInstance()->getStatistiquesDao()->getNbFilmSemaine(0);
    
    
    $dateDebut = new DateTime('2014-06-16 14:00:00');
    $offsetPourMercredi = date('N',$dateDebut->getTimestamp()) -3 + 7*$offset;
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
    //exit(var_dump($dateDebut->format('Y-m-d H:i:s')));
    $tabFilmEtSeance = array();
    $tabFilmsSemaine = Dao::getInstance()->getFilmDAO()->findFilmSemaine(-$offset);
    foreach ($tabFilmsSemaine as $i => $film){
        $tabSeance = Dao::getInstance()->getSeanceDAO()->findByFilm($film);
        $entre = Dao::getInstance()->getStatistiquesDao()->getTotalEntreFilm($film);
        $revenue = Dao::getInstance()->getStatistiquesDao()->getTotalRevenueFilm($film);
        $tabSeanceInfo = array();
        foreach ($tabSeance as $i =>$seance){
            $occupation = Dao::getInstance()->getStatistiquesDao()->getTauxOccupationSeance($seance);
            $tabSeanceInfo[$i] = array(
                'seance' => $seance,
                'occupation' => $occupation
            );
        }
        $tabFilmEtSeance[$i] = array(
            'film' => $film,
            'seance' =>$tabSeanceInfo,
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

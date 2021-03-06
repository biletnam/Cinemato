<?php
use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Distributeur;
use forms\DistributeurForm;

$statistiquesControllers = $app['controllers_factory'];

$statistiquesControllers->match('/{offset}', function (Request $request, $offset) use ($app) {
    try {
        $nbAbonne = Dao::getInstance()->getStatistiquesDao()->getNbAbonne();
        $nbFilm = Dao::getInstance()->getStatistiquesDao()->getNbFilmSemaine($offset);
        $nbSeance = Dao::getInstance()->getStatistiquesDao()->getNbSeanceSemaine($offset);
        
        $dateDebut = new DateTime('now'); // '2014-06-12 14:00:00'
        if(date('N',$dateDebut->getTimestamp()) >= 3){
            $offsetPourMercredi = date('N',$dateDebut->getTimestamp()) -3 + 7*(-$offset);
        }
        else{
            $offsetPourMercredi = date('N',$dateDebut->getTimestamp()) -3 + 7*(-$offset) + 7;
        }
        
        if($offsetPourMercredi > 0){
            $dateInterval = new DateInterval('P'.$offsetPourMercredi.'D');
            $dateDebut->sub($dateInterval);
        }
        
        else{
            $dateInterval = new DateInterval('P'.-$offsetPourMercredi.'D');
            $dateDebut->add($dateInterval);
        }
        $dateFin = new DateTime($dateDebut->format('Y-m-d H:i:s'));
        $dateInterval2 = new DateInterval("P6D");
        $dateFin->add($dateInterval2);
        //exit(var_dump($dateDebut->format('Y-m-d H:i:s')));
        $tabFilmEtSeance = array();
        $tabFilmsSemaine = Dao::getInstance()->getFilmDAO()->findFilmSemaine($offset);
        foreach ($tabFilmsSemaine as $i => $film){
            $tabSeance = Dao::getInstance()->getSeanceDAO()->findByFilmAndWeek($film, $offset);
            //exit(var_dump($tabSeance));
            $entre = Dao::getInstance()->getStatistiquesDao()->getTotalEntreFilm($film);
            $revenue = Dao::getInstance()->getStatistiquesDao()->getTotalRevenueFilm($film);
            $tabSeanceInfo = array();
            foreach ($tabSeance as $j =>$seance){
                $occupation = Dao::getInstance()->getStatistiquesDao()->getTauxOccupationSeance($seance);
                $tabSeanceInfo[$j] = array(
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
    } catch (Exception $e) {
        $app->abort(404, 'Soucis de connexion avec la base...');
    }
    

    return $app['twig']->render('pages/intranet/statistiques/list.html.twig', array(
        'nbAbonne' => $nbAbonne,
        'nbFilm' => $nbFilm,
        'nbSeance' => $nbSeance,
        'dateDebutSemaine' => $dateDebut,
        'dateFinSemaine' => $dateFin,
        'offset' => $offset,
        'tabFilmSeance' => $tabFilmEtSeance
    ));
})->value('offset', 0)->bind('intranet-statistiques-list');

$app->mount('/intranet/statistiques', $statistiquesControllers);

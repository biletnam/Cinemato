<?php

use Symfony\Component\HttpFoundation\Request;

use model\Dao\Dao;
use model\Entite\Ticket;
use forms\TicketForm;

$ticketsControllers = $app['controllers_factory'];

$ticketsControllers->get('/', function () use ($app) {
    $ticketDao = Dao::getInstance()->getTicketDAO();
    $tickets = $ticketDao->findAll();

    return $app['twig']->render('pages/vente/tickets/list.html.twig', array(
        'entities' => $tickets
    ));
})->bind('vente-tickets-list');

$ticketsControllers->get('/new', function () use ($app) {
    $personneDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seanceDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seanceDao->findAll();
    $abonnes = $personneDao->findAllAbonnes();
    $tarifs = $tarifsDao->findAll();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs));

    return $app['twig']->render('pages/vente/tickets/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-tickets-new');

$ticketsControllers->post('/create', function (Request $request) use ($app) {
    $personnesDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seancesDao = Dao::getInstance()->getSeanceDAO();

    $tarifs = $tarifsDao->findAll();
    $seances = $seancesDao->findAll();
    $abonnes = $personnesDao->findAllAbonnes();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs));

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $ticket = new Ticket();

            $vendeur = $personnesDao->findFirstVendeur();
            $ticket->setVendeur($vendeur);

            $seance = $seancesDao->find($data['seance']);
            $abonne = $personnesDao->findAbonne($data['abonne']);
            $tarif = $tarifsDao->find($data['tarif']);

            if ($seance) {
                $ticket->setSeance($seance);
            } else {
                $app['session']->getFlashBag()->add('error', 'Cette séance n\'existe pas.');
            }

            if ($abonne) {
                $ticket->setAbonne($abonne);
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce compte abonné n\'existe pas.');
            }

            if ($tarif) {
                $ticket->setTarif($tarif);
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce tarif n\'existe pas.');
            }

            if ($seance && $abonne && $tarif) {
                $ticketsDao = Dao::getInstance()->getTicketDAO();

                if ($ticketsDao->create($ticket)) {
                    $app['session']->getFlashBag()->add('success', 'Le ticket est bien enregistré !');

                    return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
                } else {
                    $app['session']->getFlashBag()->add('error', 'Le ticket n\'a pas pu être enregistré.');
                }
            }
        }
    }

    return $app['twig']->render('pages/vente/tickets/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-tickets-create');

$ticketsControllers->get('/{id}', function ($id) use ($app) {
    $ticketDao = Dao::getInstance()->getTicketDAO();
    $ticket = $ticketDao->find($id);

    if (!$ticket) {
        $app->abort(404, 'Ce ticket n\'existe pas...');
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/tickets/detail.html.twig', array(
        'entity' => $ticket,
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-tickets-detail');

$ticketsControllers->get('/{id}/edit', function ($id) use ($app) {
    $ticketDao = Dao::getInstance()->getTicketDAO();
    $ticket = $ticketDao->find($id);

    if (!$ticket) {
        $app->abort(404, 'Ce ticket n\'existe pas...');
    }

    $personneDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seanceDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seanceDao->findAll();
    $abonnes = $personneDao->findAllAbonnes();
    $tarifs = $tarifsDao->findAll();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs));

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/tickets/edit.html.twig', array(
        'entity' => $ticket,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-tickets-edit');

$ticketsControllers->post('/{id}/update', function (Request $request, $id) use ($app) {
    $ticketsDao = Dao::getInstance()->getTicketDAO();
    $ticket = $ticketsDao->find($id);

    if (!$ticket) {
        $app->abort(404, 'Ce ticket n\'existe pas...');
    }

    $personneDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seanceDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seanceDao->findAll();
    $abonnes = $personneDao->findAllAbonnes();
    $tarifs = $tarifsDao->findAll();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs));

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $seance = $seancesDao->find($data['seance']);
            $abonne = $personnesDao->findAbonne($data['abonne']);
            $tarif = $tarifsDao->find($data['tarif']);

            if ($seance) {
                $ticket->setSeance($seance);
            } else {
                $app['session']->getFlashBag()->add('error', 'Cette séance n\'existe pas.');
            }

            if ($abonne) {
                $ticket->setAbonne($abonne);
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce compte abonné n\'existe pas.');
            }

            if ($tarif) {
                $ticket->setTarif($tarif);
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce tarif n\'existe pas.');
            }

            if ($seance && $abonne && $tarif) {
                if ($ticketsDao->update($ticket)) {
                    $app['session']->getFlashBag()->add('success', 'Le ticket est bien mis à jour !');

                    return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
                } else {
                    $app['session']->getFlashBag()->add('error', 'Le ticket n\'a pas pu être mis à jour.');
                }
            }
        }
    }

    $deleteForm = $app['form.factory']->createBuilder('form', array('id' => $id))
        ->add('id', 'hidden')
        ->getForm();

    return $app['twig']->render('pages/vente/tickets/edit.html.twig', array(
        'entity' => $ticket,
        'form' => $form->createView(),
        'delete_form' => $deleteForm->createView()
    ));
})->bind('vente-tickets-update');

$ticketsControllers->post('/{id}/delete', function (Request $request, $id) use ($app) {
    $ticketDao = Dao::getInstance()->getTicketDAO();
    $ticket = $ticketDao->find($id);

    if (!$ticket) {
        $app->abort(404, 'Ce ticket n\'existe pas...');
    }

    if ($ticketDao->delete($ticket)) {
        $app['session']->getFlashBag()->add('success', 'L\'abonné a bien été supprimé !');
    } else {
        $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a pas pu être supprimé...');
    }

    return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
})->bind('vente-tickets-delete');

$app->mount('/vente/tickets', $ticketsControllers);

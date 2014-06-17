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
    $personnesDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seancesDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seancesDao->findAll();
    $abonnes = $personnesDao->findAllAbonnes();
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

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        $ticket = new Ticket();

        $vendeur = $personnesDao->findFirstVendeur();
        $ticket->setVendeur($vendeur);

        $hasClientAccount = $data['hasClientAccount'];

        if ($hasClientAccount) {
            $abonne = $personnesDao->findAbonne($data['abonne']);
            $tarif = $tarifsDao->find('Abonné');

            if ($abonne) {
                if ($abonne->getPlacesRestantes() > 0) {
                    $rechargement = $abonne->getRechargeNotEmpty();
                    $rechargement->increasePlacesUtilisees();

                    $rechargementDao = Dao::getInstance()->getRechargementDAO();

                    $rechargementUpdate = $rechargementDao->update($rechargement);

                    if ($rechargementUpdate) {
                        $ticket->setAbonne($abonne);
                    } else {
                        $app['session']->getFlashBag()->add('error', 'Erreur dans la gestion des recharges.');
                    }
                } else {
                    $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a plus de places en réserve.');
                }
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce compte abonné n\'existe pas.');
            }
        } else {
            $tarif = $tarifsDao->find($data['tarif']);
        }

        if ($tarif) {
            $ticket->setTarif($tarif);
        } else {
            $app['session']->getFlashBag()->add('error', 'Ce tarif n\'existe pas.');
        }

        $seance = $seancesDao->find($data['seance']);

        if ($seance && $tarif && ((!$hasClientAccount) || ($hasClientAccount && $rechargementUpdate))) {
            $ticket->setSeance($seance);
            $ticketsDao = Dao::getInstance()->getTicketDAO();

            if ($ticketsDao->create($ticket)) {
                $app['session']->getFlashBag()->add('success', 'Le ticket est bien enregistré !');

                return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le ticket n\'a pas pu être enregistré.');
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

    $personnesDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seancesDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seancesDao->findAll();
    $abonnes = $personnesDao->findAllAbonnes();
    $tarifs = $tarifsDao->findAll();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs), array(
        'seance' => $ticket->getSeance()->getId(),
        'abonne' => $ticket->getAbonne()->getId(),
        'tarif' => $ticket->getTarif()->getNom(),
    ));

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

    $personnesDao = Dao::getInstance()->getPersonneDAO();
    $tarifsDao = Dao::getInstance()->getTarifDAO();
    $seancesDao = Dao::getInstance()->getSeanceDAO();

    $seances = $seancesDao->findAll();
    $abonnes = $personnesDao->findAllAbonnes();
    $tarifs = $tarifsDao->findAll();

    $form = $app['form.factory']->create(new TicketForm($seances, $abonnes, $tarifs), array(
        'seance' => $ticket->getSeance()->getId(),
        'abonne' => $ticket->getAbonne()->getId(),
        'tarif' => $ticket->getTarif()->getNom(),
    ));

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        $hasClientAccount = $data['hasClientAccount'];

        if ($hasClientAccount) {
            $abonne = $personnesDao->findAbonne($data['abonne']);
            $tarif = $tarifsDao->find('Abonné');

            if ($abonne) {
                if ($abonne->getPlacesRestantes() > 0) {
                    $rechargement = $abonne->getRechargeNotEmpty();
                    $rechargement->increasePlacesUtilisees();

                    $rechargementDao = Dao::getInstance()->getRechargementDAO();

                    $rechargementUpdate = $rechargementDao->update($rechargement);

                    if ($rechargementUpdate) {
                        $ticket->setAbonne($abonne);
                    } else {
                        $app['session']->getFlashBag()->add('error', 'Erreur dans la gestion des recharges.');
                    }
                } else {
                    $app['session']->getFlashBag()->add('error', 'L\'abonné n\'a plus de places en réserve.');
                }
            } else {
                $app['session']->getFlashBag()->add('error', 'Ce compte abonné n\'existe pas.');
            }
        } else {
            $tarif = $tarifsDao->find($data['tarif']);
        }

        if ($tarif) {
            $ticket->setTarif($tarif);
        } else {
            $app['session']->getFlashBag()->add('error', 'Ce tarif n\'existe pas.');
        }

        $seance = $seancesDao->find($data['seance']);

        if ($seance && $tarif && ((!$hasClientAccount) || ($hasClientAccount && $rechargementUpdate))) {
            $ticket->setSeance($seance);

            if ($ticketsDao->update($ticket)) {
                $app['session']->getFlashBag()->add('success', 'Le ticket est bien mis à jour !');

                return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le ticket n\'a pas pu être mis à jour.');
            }
        } else {
            $app['session']->getFlashBag()->add('error', 'Cette séance n\'existe pas.');
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

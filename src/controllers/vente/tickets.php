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

    $vendeur = $personneDao->findFirstVendeur();

    echo '<pre>';
    exit(var_dump($vendeur));

    $ticket = new Ticket();
    $form = $app['form.factory']->create(new TicketForm(), $ticket);

    return $app['twig']->render('pages/vente/tickets/new.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('vente-tickets-new');

$ticketsControllers->post('/create', function (Request $request) use ($app) {
    $ticket = new Ticket();
    $form = $app['form.factory']->create(new TicketForm(), $ticket);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $ticketDao = Dao::getInstance()->getTicketDAO();

            if ($ticketDao->create($ticket)) {
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

    $form = $app['form.factory']->create(new TicketForm(), $ticket);

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
    $ticketDao = Dao::getInstance()->getTicketDAO();
    $ticket = $ticketDao->find($id);

    if (!$ticket) {
        $app->abort(404, 'Ce ticket n\'existe pas...');
    }

    $form = $app['form.factory']->create(new TicketForm(), $ticket);

    if ($request->getMethod() === 'POST') {
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($ticketDao->update($ticket)) {
                $app['session']->getFlashBag()->add('success', 'Le ticket est bien mis à jour !');

                return $app->redirect($app['url_generator']->generate('vente-tickets-list'));
            } else {
                $app['session']->getFlashBag()->add('error', 'Le ticket n\'a pas pu être mis à jour.');
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

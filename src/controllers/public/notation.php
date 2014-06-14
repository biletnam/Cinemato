<?php
use model\Dao\Dao;
use model\Entite\Ticket;
use Symfony\Component\HttpFoundation\Request;

$notationControllers = $app['controllers_factory'];

$notationControllers->get('/notation', function () use ($app) {
    $form = $app['form.factory']->create(new NotationForm());

    return $app['twig']->render('pages/public/notation.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('public-notation');
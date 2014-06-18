<?php
use model\Dao\Dao;
use model\Entite\Ticket;
use forms\NotationForm;
use Symfony\Component\HttpFoundation\Request;

$notationControllers = $app['controllers_factory'];

$notationControllers->get('/', function () use ($app) {
    $form = $app['form.factory']->create(new NotationForm());

    return $app['twig']->render('pages/public/notation.html.twig', array(
        'form' => $form->createView()
    ));
})->bind('public-notation');

$notationControllers->post('/save', function (Request $request) use ($app) {
	$form = $app['form.factory']->create(new NotationForm());

	if ($request->getMethod() === 'POST') {
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			$data = $form->getData();
			if($data['note'] < 0 || $data['note'] > 20){
				$app['session']->getFlashBag()->add('Erreur', 'Saisissez une note entre 0 et 20 !');
			}
			else
			{
				$ticket = new Ticket();
				$ticketDao = Dao::getInstance()->getTicketDao();
				$ticket = $ticketDao->find($data['id']);
				if($ticket != NULL){
					if($ticket->getNote() != NULL)
						$app['session']->getFlashBag()->add('Erreur', 'Vous avez déja noté ce film avec ce ticket !');
					else {
						$ticket->setNote($data['note']);
						try{
						$ticketDao->update($ticket);
						}catch (exception $e) 
						{ 
							$app['session']->getFlashBag()->add('error', $e->getMessage());
								return $app['twig']->render('pages/public/notation.html.twig', array(
								'form' => $form->createView()));
		   				} 
						$app['session']->getFlashBag()->add('Validé', 'Vote note a bien enregistrée !');
						return $app->redirect($app['url_generator']->generate('public'));
					}
				}
				else
				{
					$app['session']->getFlashBag()->add('error', 'Ce ticket n\'existe pas !');
				}
			}
		}
	}
	return $app['twig']->render('pages/public/notation.html.twig', array(
			'form' => $form->createView()
	));
})->bind('public-notation-save');



$app->mount('/public/notation', $notationControllers);
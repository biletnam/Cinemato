<?php

use \Exception;

use Silex\Application,
    Silex\Provider\UrlGeneratorServiceProvider,
    Silex\Provider\MonologServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\FormServiceProvider,
    Silex\Provider\TranslationServiceProvider,
    Silex\Provider\SessionServiceProvider;

use DerAlex\Silex\YamlConfigServiceProvider;
use Entea\Twig\Extension\AssetExtension;

$app = new Application();

$app->register(new SessionServiceProvider());

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile'       => __DIR__.'/log/app.log',
    'monolog.name'          => 'kp_app',
    'monolog.level'         => 300 // = Logger::WARNING
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/../views')
));

$app->register(new YamlConfigServiceProvider(__DIR__ . '/config/parameters.yml'));

$twig = $app['twig'];
$twig->addExtension(new AssetExtension($app));

$app->error(function (Exception $exception, $statusCode) use ($app) {
    $template = '404';

    switch ($statusCode) {
        case 500:
            $template = '500';
            break;
        case 404:
        default:
            break;
    }

    return $app['twig']->render('pages/errors/' . $template . '.html.twig', array(
        'error' => array(
            'statusCode' => $statusCode,
            'exception' => $exception
        )
    ));
});

return $app;

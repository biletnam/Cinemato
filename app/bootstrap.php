<?php

use Silex\Application,
    Silex\Provider\UrlGeneratorServiceProvider,
    Silex\Provider\MonologServiceProvider,
    Silex\Provider\TwigServiceProvider;

use Entea\Twig\Extension\AssetExtension;

$app = new Application();

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile'       => __DIR__.'/log/app.log',
    'monolog.name'          => 'kp_app',
    'monolog.level'         => 300 // = Logger::WARNING
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/../views')
));

$twig = $app['twig'];
$twig->addExtension(new AssetExtension($app));

return $app;

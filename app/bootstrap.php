<?php

use Silex\Application,
    Silex\Provider\UrlGeneratorServiceProvider,
    Silex\Provider\MonologServiceProvider,
    Silex\Provider\TwigServiceProvider;

use DerAlex\Silex\YamlConfigServiceProvider;
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

$app->register(new YamlConfigServiceProvider(__DIR__ . '/config/parameters.yml'));

$twig = $app['twig'];
$twig->addExtension(new AssetExtension($app));

return $app;

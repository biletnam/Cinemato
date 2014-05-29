<?php

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

return $app;

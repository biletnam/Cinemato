<?php

require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app = new Application();

if ((isset($app_env)) && (in_array($app_env, array('dev','test')))) {
    $app['env'] = $app_env;
    $app['debug'] = true;
} else {
    if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))) {
        $app['env'] = 'prod';
    } else {
        $app['env'] = 'dev';
    }
}

if ($app['env'] === 'test') {
    return $app;
} else {
    $app->register(new UrlGeneratorServiceProvider());

    $app->register(new TwigServiceProvider(), array(
        'twig.path' => __DIR__ . '/views',
    ));

    $app->get('/', function () use ($app) {
        return 'Home Page';
    });

    $app->run();
}

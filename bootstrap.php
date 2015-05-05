<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app->register( new DerAlex\Silex\YamlConfigServiceProvider( __DIR__ . '/config/config.yml' ) );

$app['app-config'] = $app->share( function() use( $app ) {
	return new Wikibot\Config( $app['config'] );
} );

$app->register( new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/templates',
) );

//$app->run();

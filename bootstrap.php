<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app->register( new DerAlex\Silex\YamlConfigServiceProvider( __DIR__ . '/config/config.yml' ) );

$app->register( new Knp\Provider\ConsoleServiceProvider(), array(
    'console.name' => 'Wikibot',
    'console.version' => '1.0.0',
    'console.project_directory' => __DIR__ . '/src/Console'
) );

$app['app-config'] = $app->share( function() use( $app ) {
	return new Wikibot\Config( $app['config'] );
} );

//$app->run();

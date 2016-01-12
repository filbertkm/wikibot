<?php

require_once __DIR__ . '/vendor/autoload.php';

ini_set( 'display_errors', 1 );
error_reporting( -1 );

\Symfony\Component\Debug\ErrorHandler::register();

if ( 'cli' !== php_sapi_name() ) {
	\Symfony\Component\Debug\ExceptionHandler::register();
}

//$app->run();

<?php

require_once __DIR__ . '/vendor/autoload.php';

\Symfony\Component\Debug\ErrorHandler::register();

if ( 'cli' !== php_sapi_name() ) {
	\Symfony\Component\Debug\ExceptionHandler::register();
}

//$app->run();

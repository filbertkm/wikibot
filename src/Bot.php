<?php

namespace Wikibot;

use DerAlex\Silex\YamlConfigServiceProvider;
use Filbertkm\Http\HttpClient;
use Knp\Command\Command;
use Knp\Provider\ConsoleServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Wikibot\Config;
use Wikibot\Console\CommandRegistry;

class Bot {

	private $app;

	private $console;

	private $commandRegistry;

	public function __construct() {
		$this->app = new Application();
		$this->app['debug'] = true;

		$this->init();

		$this->commandRegistry = new CommandRegistry(
			new HttpClient( 'Wikibot', 'wikibot' ),
			$this->app['app-config']
		);

		$this->initCommands();
	}

	public function init() {
		$this->app->register( new YamlConfigServiceProvider( __DIR__ . '/../config/config.yml' ) );

		$this->app['app-config'] = $this->app->share( function() {
			return new Config( $this->app['config'] );
		} );

		$this->app->register( new TwigServiceProvider(), array(
			'twig.path' => __DIR__ . '/../templates',
		) );

		$this->app->register( new MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__ . '/../log/debug.log'
		) );

		$this->app->register( new ConsoleServiceProvider(), array(
			'console.name' => 'Wikibot',
			'console.version' => '1.0.0',
			'console.project_directory' => __DIR__ . '/Console'
		) );

		$this->console = $this->app['console'];
	}

	private function initCommands() {
		foreach ( $this->commandRegistry->getCommands() as $command ) {
			$this->console->add( $command );
		}
	}

	public function registerCommand( Command $command ) {
		$this->console->add( $command );
	}

	public function run() {
		$this->console->run();
	}

}

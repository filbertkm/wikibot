<?php

namespace Wikibot;

use DerAlex\Silex\YamlConfigServiceProvider;
use Knp\Command\Command;
use Knp\Provider\ConsoleServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Wikibot\Config;
use Wikibot\Console\CommandRegistry;

class Bot {

	private $configFile;

	private $logDir;

	private $app;

	private $console;

	private $commandRegistry;

	public function __construct( $configFile, $logDir ) {
		$this->configFile = $configFile;
		$this->logDir = $logDir;

		$this->app = new Application();
		$this->commandRegistry = new CommandRegistry();

		$this->init();
	}

	public function init() {
		$this->app->register( new YamlConfigServiceProvider( $this->configFile ) );

		$this->app['app-config'] = $this->app->share( function() {
			return new Config( $this->app['config'] );
		} );

		$this->app->register( new TwigServiceProvider(), array(
			'twig.path' => __DIR__ . '/../templates',
		) );

		$this->app->register( new MonologServiceProvider(), array(
			'monolog.logfile' => $this->logDir . '/debug.log'
		) );

		$this->app->register( new ConsoleServiceProvider(), array(
			'console.name' => 'Wikibot',
			'console.version' => '1.0.0',
			'console.project_directory' => __DIR__ . '/Console'
		) );

		$this->console = $this->app['console'];
		$this->initCommands();
	}

	public function getApplication() {
		return $this->app;
	}

	private function initCommands() {
		foreach( $this->commandRegistry->getCommands() as $command ) {
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

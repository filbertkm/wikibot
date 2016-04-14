<?php

namespace Wikibot;

use Filbertkm\Http\HttpClient;
use Knp\Command\Command;
use Knp\Provider\ConsoleServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Yaml\Yaml;
use Wikibot\Config;
use Wikibot\Console\CommandRegistry;
use Wikibot\Query\QueryServiceProvider;

class Bot {

	private $app;

	private $console;

	private $commandRegistry;

	public function __construct() {
		$this->app = new Application();
		$this->app['debug'] = true;

		$this->init();

		$this->commandRegistry = new CommandRegistry(
			$this->app,
			new HttpClient( 'Wikibot', 'wikibot' ),
			$this->app['app-config']
		);

		$this->initCommands();
	}

	public function init() {
		$this->app['app-config'] = $this->app->share( function() {
			return new Config( Yaml::parse( file_get_contents( __DIR__ . '/../config/config.yml' ) ) );
		} );

		$this->app->register( new QueryServiceProvider(), array(
			'query.url' => 'https://query.wikidata.org/bigdata/namespace/wdq/sparql',
			'query.prefixes' => array(
				'wikibase' => 'http://wikiba.se/ontology#',
				'wd' => 'http://www.wikidata.org/entity/',
				'wdt' => 'http://www.wikidata.org/prop/direct/',
				'p' => 'http://www.wikidata.org/prop/',
				'psv' => 'http://www.wikidata.org/prop/statement/value/',
				'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#'
			)
		) );

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

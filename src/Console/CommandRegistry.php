<?php

namespace Wikibot\Console;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Console\Commands\ImportSitesCommand;
use MediaWiki\Sites\Lookup\YamlSiteLookup;
use Silex\Application;
use Symfony\Component\Yaml\Yaml;
use Wikibot\ApiClientFactory;
use Wikibot\Config;
use Wikibot\Query\Command\QueryCommand;
use Wikibot\Wikibase\Command\FlipCoordinatesCommand;

class CommandRegistry {

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var HttpClient
	 */
	private $httpClient;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var ApiClientFactory|null
	 */
	private $apiClientFactory = null;

	public function __construct( Application $app, HttpClient $httpClient, Config $config ) {
		$this->app = $app;
		$this->httpClient = $httpClient;
		$this->config = $config;
	}

	public function getCommands() {
		$commands = array(
			$this->newFlipCoordinatesCommand(),
			$this->newGetCommand(),
			$this->newImportSitesCommand(),
			$this->newPostCommand(),
			$this->newQueryCommand()
		);

		foreach ( $this->getApiCommands() as $apiCommand ) {
			$commands[] = $apiCommand;
		}

		return $commands;
	}

	private function newImportSitesCommand() {
		$sites = $this->config->get( 'sites' );

		$command = new ImportSitesCommand();
		$command->setServices(
			$this->httpClient,
			'https://meta.wikimedia.org/w/api.php',
			$sites['path']
		);

		return $command;
	}

	private function newQueryCommand() {
		$command = new QueryCommand();
		$command->setServices(
			$this->app['query-builder'],
			$this->app['query-runner']
		);

		return $command;
	}

	private function newFlipCoordinatesCommand() {
		$command = new FlipCoordinatesCommand();
		$command->setServices(
			$this->getApiClientFactory(),
			$this->app['query-builder'],
			$this->app['query-runner']
		);

		return $command;
	}

	private function newGetCommand() {
		return new \Wikibot\Console\Commands\GetCommand();
	}

	private function newPostCommand() {
		return new \Wikibot\Console\Commands\PostCommand();
	}

	private function getApiCommands() {
		$apiCommandClasses = array(
			'\Wikibot\MediaWiki\Command\CategoryMembersCommand',
			'\Wikibot\MediaWiki\Command\CreatePageCommand',
			'\Wikibot\Wikibase\Command\AddStatementCommand',
			'\Wikibot\Wikibase\Command\BatchAddStatementCommand',
			'\Wikibot\Wikibase\Command\CreateEntityCommand',
			'\Wikibot\Wikibase\Command\FixP131Command',
			'\Wikibot\Wikibase\Command\SetLabelCommand',
			'\Wikibot\Wikibase\Command\ValueFinderCommand',
			'\Wikibot\Wikibase\Command\ViewEntityCommand'
		);

		foreach ( $apiCommandClasses as $apiCommandClass ) {
			$command = new $apiCommandClass();
			$command->setServices( $this->getApiClientFactory() );

			$commands[] = $command;
		}

		return $commands;
	}

	/**
	 * @return ApiClientFactory
	 */
	private function getApiClientFactory() {
		if ( $this->apiClientFactory === null ) {
			$this->apiClientFactory = $this->newApiClientFactory();
		}

		return $this->apiClientFactory;
	}

	/**
	 * @return ApiClientFactory
	 */
	private function newApiClientFactory() {
		$usersConfig = $this->config->get( 'users' );
		$users = Yaml::parse( file_get_contents( $usersConfig['path'] ) );

		$sites = $this->config->get( 'sites' );

		return new ApiClientFactory(
			$this->httpClient,
			new YamlSiteLookup( $sites['path'] ),
			$users
		);
	}

}

<?php

namespace Wikibot\Console;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Console\Commands\ImportSitesCommand;
use MediaWiki\Sites\Lookup\YamlSiteLookup;
use Silex\Application;
use Symfony\Component\Yaml\Yaml;
use Wikibot\ApiClientFactory;
use Wikibot\Console\Commands\FlipCoordinatesCommand;
use Wikibot\Console\Commands\QueryCommand;
use Wikibot\Config;

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
			'\Wikibot\Console\Commands\Api\AddStatementCommand',
			'\Wikibot\Console\Commands\Api\BatchAddStatementCommand',
			'\Wikibot\Console\Commands\Api\CategoryMembersCommand',
			'\Wikibot\Console\Commands\Api\CreateEntityCommand',
			'\Wikibot\Console\Commands\Api\FixP131Command',
			'\Wikibot\Console\Commands\Api\SetLabelCommand',
			'\Wikibot\Console\Commands\Api\ValueFinderCommand',
			'\Wikibot\Console\Commands\Api\ViewEntityCommand'
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
		$siteLookup = new YamlSiteLookup( $sites['path'] );

		return new ApiClientFactory(
			$this->httpClient,
			new YamlSiteLookup( $sites['path'] ),
			$users
		);
	}

}

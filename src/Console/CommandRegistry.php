<?php

namespace Wikibot\Console;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Console\Commands\ImportSitesCommand;
use MediaWiki\Sites\Lookup\YamlSiteLookup;
use Silex\Application;
use Symfony\Component\Yaml\Yaml;
use Wikibot\ApiClientFactory;
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
			$this->app['query-runner']
		);

		return $command;
	}

	private function newPostCommand() {
		return new \Wikibot\Console\Commands\PostCommand();
	}

	private function getApiCommands() {
		$apiCommandClasses = array(
			'\Wikibot\Console\Commands\AddStatementCommand',
			'\Wikibot\Console\Commands\BatchAddStatementCommand',
			'\Wikibot\Console\Commands\CategoryMembersCommand',
			'\Wikibot\Console\Commands\FixP131Command',
			'\Wikibot\Console\Commands\SetLabelCommand',
			'\Wikibot\Console\Commands\ValueFinderCommand',
			'\Wikibot\Console\Commands\ViewEntityCommand'
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

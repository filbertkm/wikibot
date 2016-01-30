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
			'\Wikibot\Console\Commands\CategoryMembersCommand',
			'\Wikibot\Console\Commands\SetLabelCommand',
		);

		$usersConfig = $this->config->get( 'users' );
		$users = Yaml::parse( file_get_contents( $usersConfig['path'] ) );

		$sites = $this->config->get( 'sites' );
		$siteLookup = new YamlSiteLookup( $sites['path'] );

		foreach ( $apiCommandClasses as $apiCommandClass ) {
			$command = new $apiCommandClass();
			$command->setServices(
				new ApiClientFactory( $this->httpClient, $siteLookup, $users )
			);

			$commands[] = $command;
		}

		return $commands;
	}

}

<?php

namespace Wikibot\Console;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Console\Commands\ImportSitesCommand;
use MediaWiki\Sites\Lookup\YamlSiteLookup;
use Wikibot\ApiClientFactory;
use Wikibot\Config;
use Symfony\Component\Yaml\Yaml;

class CommandRegistry {

	/**
	 * @var Config
	 */
	private $config;

	public function __construct( HttpClient $httpClient, Config $config ) {
		$this->httpClient = $httpClient;
		$this->config = $config;
	}

	public function getCommands() {
		$commands = array(
			$this->newImportSitesCommand(),
			$this->newPostCommand()
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

	private function newPostCommand() {
		return new \Wikibot\Console\Commands\PostCommand();
	}

	private function getApiCommands() {
		$apiCommandClasses = array(
			'\Wikibot\Console\Commands\AddStatementCommand',
			'\Wikibot\Console\Commands\CategoryMembersCommand',
			'\Wikibot\Console\Commands\EditEntityCommand',
			'\Wikibot\Console\Commands\EditPageCommand',
			'\Wikibot\Console\Commands\PurgeCommand',
			'\Wikibot\Console\Commands\SetLabelCommand',
			'\Wikibot\Console\Commands\SetReferenceCommand',
			'\Wikibot\Console\Commands\SetStatementCommand',
			'\Wikibot\Console\Commands\UndoCommand',
			'\Wikibot\Console\Commands\Upload\FileUploadCommand',
			'\Wikibot\Console\Commands\Upload\UploadCommand'
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

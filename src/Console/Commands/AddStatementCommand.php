<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;

class AddStatementCommand extends Command {

	protected function configure() {
		$this->setName( 'add-statement' )
			->setDescription( 'Add a statement' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'devrepo' );

		$apiClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$wiki
		);

		$params = array(
			'action' => 'query',
			'list' => 'recentchanges'
		);

		$response = $apiClient->get( $params );

		$output->write( $response );
	}

}

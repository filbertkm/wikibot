<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\WikibaseClient;

class AddStatementCommand extends Command {

	/**
	 * @var ApiClientFactory
	 */
	private $apiClientFactory;

	/**
	 * @param ApiClientFactory $apiClientFactory
	 */
	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'add-statement' )
			->setDescription( 'Add a statement' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( 'devrepo' );
		$wikibaseClient = new WikibaseClient( $apiClient );

		$entityId = 'Q888';
		$propertyId = 'P853';
		$value = json_decode( '{"entity-type":"item","numeric-id":4903}' );

		$response = $wikibaseClient->createClaim( $entityId, $propertyId, $value );

		echo "post response\n";
		var_export( $response );
	}

}

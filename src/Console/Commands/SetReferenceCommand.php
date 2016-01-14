<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\WikibaseClient;

class SetReferenceCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'set-reference' )
			->setDescription( 'Set reference' )
			->addArgument(
				'entity-id',
				InputArgument::REQUIRED,
				'Entity ID'
			)
			->addArgument(
				'property-id',
				InputArgument::REQUIRED,
				'Property ID'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( 'devrepo' );

		$entityId = $input->getArgument( 'entity-id' );
		$propertyId = $input->getArgument( 'property-id' );

		$wikibaseClient = new WikibaseClient( $apiClient );
		$data = $wikibaseClient->getClaims( $entityId, $propertyId );

		$statements = $data['claims'][$propertyId];

		if ( count( $statements ) !== 1 ) {
			return;
		}

		$references = $statements[0]['references'];
		$guid = $statements[0]['id'];

		if ( count( $references ) !== 1 ) {
			return;
		}

		$refSnaks = $references[0]['snaks'];

		$snaksOrder = array( 'P486', 'P605' );
//		$snaksOrder = array( 'P605', 'P486' );

		$referenceHash = $references[0]['hash'];

		$baseRev = 24128;

		$response = $wikibaseClient->setReference(
			$guid,
			$refSnaks,
			$baseRev,
			$snaksOrder,
			$referenceHash
		);

		var_export( $response );
	}

}

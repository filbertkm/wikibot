<?php

namespace Wikibot\Wikibase\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
use Wikibot\Wikibase\SnakBuilder;
use Wikibot\Wikibase\StatementCreator;

class BatchAddStatementCommand extends Command {

	/**
	 * @var ApiClientFactory
	 */
	private $apiClientFactory;

	/**
	 * @var ApiClient
	 */
	private $apiClient;

	/**
	 * @param ApiClientFactory $apiClientFactory
	 */
	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'batch-add-statement' )
			->setDescription( 'Add a statement' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki ID'
			)
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				'File'
			)
			->addArgument(
				'property',
				InputArgument::REQUIRED,
				'Property ID'
			)
			->addArgument(
				'value',
				InputArgument::REQUIRED,
				'Value ID'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$this->apiClient = $this->apiClientFactory->newApiClient(
			$input->getArgument( 'wiki' )
		);

		$this->apiClient->login();
		$apiEntityLookup = new ApiEntityLookup( $this->apiClient );

		$itemIds = array_map( 'trim', file( $input->getArgument( 'file' ) ) );

		foreach ( $itemIds as $itemId ) {
			$propertyId = $input->getArgument( 'property' );

			$entityRevision = $apiEntityLookup->getEntity( $itemId );
			$item = $entityRevision->getItem();

			if ( $item->getStatementGroupList()->hasStatementGroup( $propertyId ) ) {
				$output->writeln( "$itemId already has statement(s) for $propertyId" );
				continue;
			}

			$response = $this->addStatement(
				$itemId,
				$propertyId,
				$input->getArgument( 'value' ),
				$entityRevision->getRevisionId()
			);

			$this->report( $output, $response, $itemId );

			sleep( 2 );
		}

		$this->apiClient->logout();
	}

	private function addStatement( $itemId, $propertyId, $value, $revisionId ) {
		$apiEntityLookup = new ApiEntityLookup( $this->apiClient );
		$entityRevision = $apiEntityLookup->getEntity( $itemId );

		$snakBuilder = new SnakBuilder();
		$statementCreator = new StatementCreator( $this->apiClient );

		$statementCreator->newStatement( $itemId, $propertyId, $value );

		$statementCreator->addReference( array(
			'P143' => array( $snakBuilder->getEntityIdValueSnak( 'P143', 'Q328' ) )
		) );

		return $statementCreator->create( $revisionId );
	}

	private function report( $output, $response, $itemId ) {
		if ( isset( $response['success' ] ) ) {
			$output->writeln( "Added statement to $itemId" );
		} else {
			$output->writeln( "Failed to add statement to $itemId" );
		}
	}

}

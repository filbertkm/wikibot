<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;

class ValueFinderCommand extends Command {

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
		$this->setName( 'value-finder' )
			->setDescription( 'Find items with property value' )
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
				$statements = $item->getStatementGroupList()->getStatementGroup( $propertyId );

				foreach ( $statements as $statement ) {
					if ( $this->statementHasValue( $statement, $input->getArgument( 'value' ) ) ) {
						$output->writeln( "$itemId has statement(s) for $propertyId" );
					}
				}
			}
		}

		$this->apiClient->logout();
	}

	private function statementHasValue( $statement, $value ) {
		$statementValue = $statement->getMainSnak()->getDataValue()->getEntityId();

		return $statementValue === $value;
	}

}

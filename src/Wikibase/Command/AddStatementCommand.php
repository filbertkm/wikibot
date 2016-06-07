<?php

namespace Wikibot\Wikibase\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
use Wikibot\Wikibase\SnakBuilder;
use Wikibot\Wikibase\StatementCreator;

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
			->setDescription( 'Add a statement' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki ID'
			)
			->addArgument(
				'item',
				InputArgument::REQUIRED,
				'Item ID'
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
		$apiClient = $this->apiClientFactory->newApiClient(
			$input->getArgument( 'wiki' )
		);

		$apiEntityLookup = new ApiEntityLookup( $apiClient );

		$itemId = $input->getArgument( 'item' );
		$entityRevision = $apiEntityLookup->getEntity( $itemId );

		$apiClient->login();

		$snakBuilder = new SnakBuilder();
		$statementCreator = new StatementCreator( $apiClient );

		$statementCreator->newStatement(
			$input->getArgument( 'item' ),
			$input->getArgument( 'property' ),
			$input->getArgument( 'value' )
		);

		$statementCreator->addReference( array(
			'P143' => array( $snakBuilder->getEntityIdValueSnak( 'P143', 'Q328' ) )
		) );

		$response = $statementCreator->create(
			$entityRevision->getRevisionId()
		);

		if ( isset( $response['success' ] ) ) {
			$output->writeln( "Added statement to $itemId" );
		} else {
			$output->writeln( "Failed to add statement to $itemId" );
		}
	}

}

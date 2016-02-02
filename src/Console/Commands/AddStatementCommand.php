<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
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
				'year',
				InputArgument::REQUIRED,
				'Year'
			)
			->addArgument(
				'shadow',
				InputArgument::REQUIRED,
				'saw shadow?'
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
		$statementCreator = new StatementCreator( $apiClient );

		$valueId = $input->getArgument( 'shadow' ) === 'no' ? 'Q22443758' : 'Q22443759';
		$propertyId = 'P793';
		$year = $input->getArgument( 'year' );

		$response = $statementCreator->create(
			$itemId,
			$propertyId,
			$valueId,
			$year,
			$entityRevision->getRevisionId()
		);

		var_export( $response );
	}

}

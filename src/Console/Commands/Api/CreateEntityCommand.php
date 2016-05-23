<?php

namespace Wikibot\Console\Commands\Api;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class CreateEntityCommand extends Command {

	private $apiClientFactory;

	protected function configure() {
		$this->setName( 'create-entity' )
			->setDescription( 'Create a new entity' )
			->addArgument(
				'entity-type',
				InputArgument::REQUIRED,
				'Entity type'
			)
			->addArgument(
				'wiki',
				InputArgument::OPTIONAL,
				'Wiki id',
				'devrepo'
			);
	}

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( $input->getArgument( 'wiki' ) );
		$apiClient->login();

		$params = [
			'action' => 'wbeditentity',
			'new' => $input->getArgument( 'entity-type' ),
			'data' => json_encode( [] )
		];

		$res = $apiClient->doEdit( $params );

		var_export( $res );
	}

}

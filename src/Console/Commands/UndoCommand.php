<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class UndoCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'undo' )
			->setDescription( 'Undo a revision' )
			->addArgument(
				'title',
				InputArgument::REQUIRED,
				'Title'
			)
			->addArgument(
				'revision',
				InputArgument::REQUIRED,
				'Revision'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( 'devrepo' );

		$params = array(
			'action' => 'edit',
			'title' => $input->getArgument( 'title' ),
			'undo' => $input->getArgument( 'revision' ),
			'text' => 'ooomg'
		);

		$apiClient->login();
		$response = $apiClient->post( $params );

		echo "post response\n";
		var_export( $response );
	}

}

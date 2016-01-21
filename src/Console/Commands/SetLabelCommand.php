<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class SetLabelCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'set-label' )
			->setDescription( 'Set a label' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki ID'
			)
			->addArgument(
				'id',
				InputArgument::REQUIRED,
				'Entity ID'
			)
			->addArgument(
				'label',
				InputArgument::REQUIRED,
				'Label'
			)
			->addArgument(
				'baserev',
				InputArgument::REQUIRED,
				'Baserev'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient(
			$input->getArgument( 'wiki' )
		);

		$params = array(
			'action' => 'wbsetlabel',
			'id' => $input->getArgument( 'id' ),
			'value' => $input->getArgument( 'label' ),
			'language' => 'en',
			'baserevid' => $input->getArgument( 'baserev' )
		);

		$apiClient->login();
		$response = $apiClient->post( $params );
		$apiClient->logout();

		var_export( $response );
	}

}

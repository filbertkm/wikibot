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
		$apiClient = $this->apiClientFactory->newApiClient( 'testwikidatawiki' );

		$params = array(
			'action' => 'wbsetlabel',
			'id' => $input->getArgument( 'id' ),
			'value' => $input->getArgument( 'label' ),
			'language' => 'en-ca',
			'baserevid' => $input->getArgument( 'baserev' ),
//			'summary' => 'debugging T102148'
		);

		$apiClient->login();
		$response = $apiClient->post( $params );

		echo "post response\n";
		var_export( $response );
	}

}

<?php

namespace Wikibot\Console\Commands\Api;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\MediaWiki\PageCreator;

class CreatePageCommand extends Command {

	private $apiClientFactory;

	protected function configure() {
		$this->setName( 'create-page' )
			->setDescription( 'Create a new page' )
			->addArgument(
				'title',
				InputArgument::REQUIRED,
				'Title'
			)
			->addArgument(
				'wiki',
				InputArgument::OPTIONAL,
				'Wiki id'
			);
	}

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( $input->getArgument( 'wiki' ) );
		$apiClient->login();

		$pageCreator = new PageCreator( $apiClient );
		$res = $pageCreator->create( $input->getArgument( 'title' ), 'kittens' );

		var_export( $res );
	}

}

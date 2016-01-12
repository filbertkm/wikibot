<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\MediaWiki\Page;

class CategoryMembersCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'cat-members' )
			->setDescription( 'Category members' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki'
			)
			->addArgument(
				'category',
				InputArgument::REQUIRED,
				'Category'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( $input->getArgument( 'wiki' ) );
		$apiClient->login();

		$params = array(
			'action' => 'query',
			'gcmtitle' => 'Category:' . $input->getArgument( 'category' ),
			'gcmlimit' => 500,
			'generator' => 'categorymembers',
			'prop' => 'pageprops',
			'ppprop' => 'wikibase_item'
		);

		$response = $apiClient->get( $params );

		$pages = $response['query']['pages'];
		$pageIds = array();

		foreach ( $pages as $page ) {
			$pageIds[] = $page['pageid'];
		}

		var_export( $pages );
	}

}

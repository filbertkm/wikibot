<?php

namespace Wikibot\MediaWiki\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class CategoryMembersCommand extends Command {

	private $apiClientFactory;

	private $depth = 0;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'cat-members' )
			->setDescription( 'Category members' )
			->addArgument(
				'output',
				InputArgument::REQUIRED,
				'Output file'
			)
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
		$wikiId = $input->getArgument( 'wiki' );

		$apiClient = $this->apiClientFactory->newApiClient( $wikiId );
		$apiClient->login();

		$params = array(
			'action' => 'query',
			'gcmtitle' => 'Category:' . $input->getArgument( 'category' ),
			'gcmnamespace' => 0,
			'gcmlimit' => 500,
			'gcmtype' => 'page|subcat',
			'generator' => 'categorymembers',
			'prop' => 'pageprops',
			'ppprop' => 'wikibase_item'
		);

		$response = $apiClient->get( $params );
		$pages = $response['query']['pages'];

		$itemIds = array();
		$subcats = array();

		foreach ( $pages as $pageData ) {
			if ( $pageData['ns'] === 14 ) {
				$subcats[] = $pageData['title'];
			} else if ( isset( $pageData['pageprops']['wikibase_item'] ) ) {
				$itemIds[] = $pageData['pageprops']['wikibase_item'];
			}
		}

		file_put_contents( $input->getArgument( 'output' ), implode( "\n", $itemIds ) );
	}



}

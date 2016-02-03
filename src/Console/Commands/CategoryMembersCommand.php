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
			'gcmtype' => 'page',
			'generator' => 'categorymembers',
			'prop' => 'pageprops',
			'ppprop' => 'wikibase_item'
		);

		$response = $apiClient->get( $params );

		$pages = $response['query']['pages'];
		$pageIds = array();
		$itemIds = array();

		foreach ( $pages as $pageData ) {
			$page = new Page( $pageData['title'], $pageData['ns'], $wikiId, $pageData['pageid'] );

			if ( isset( $pageData['pageprops']['wikibase_item'] ) ) {
				$page->setItemId( $pageData['pageprops']['wikibase_item'] );
				$itemIds[] = $pageData['pageprops']['wikibase_item'];
			}

			$pages[] = $page;
		}

		file_put_contents( $input->getArgument( 'output' ), implode( "\n", $itemIds ) );
	}

}

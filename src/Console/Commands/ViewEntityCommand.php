<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
use Wikibot\Wikibase\Formatters\ConsoleItemFormatter;

class ViewEntityCommand extends Command {

	private $apiClientFactory;

	protected function configure() {
		$this->setName( 'view-entity' )
			->setDescription( 'View a Wikibase entity' )
			->addArgument(
				'id',
				InputArgument::REQUIRED,
				'Entity ID'
			)
			->addArgument(
				'wiki',
				InputArgument::OPTIONAL,
				'Wiki id',
				'wikidatawiki'
			)
			->addArgument(
				'lang',
				InputArgument::OPTIONAL,
				'Language code',
				'en'
			);
	}

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( $input->getArgument( 'wiki' ) );
		$apiEntityLookup = new ApiEntityLookup( $apiClient );

		$entityRevision = $apiEntityLookup->getEntity( $input->getArgument( 'id' ) );
		$item = $entityRevision->getItem();

		$itemFormatter = new ConsoleItemFormatter();
		$text = $itemFormatter->format( $item, $input->getArgument( 'lang' ) );

		$output->writeln( $text );
	}

}

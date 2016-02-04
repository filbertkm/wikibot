<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
use Wikibot\Wikibase\Formatters\ConsoleItemFormatter;
use Wikibot\Wikibase\StatementRemover;

class FixP131Command extends Command {

	private $apiClientFactory;

	protected function configure() {
		$this->setName( 'fix-P131' )
			->setDescription( 'Remove redundant P131 statements' )
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				'Item list file'
			)
			->addArgument(
				'to-remove',
				InputArgument::REQUIRED,
				'Value to remove'
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
		$apiClient->login();

		$apiEntityLookup = new ApiEntityLookup( $apiClient );
		$statementRemover = new StatementRemover( $apiClient );

		$ids = array_map( 'trim', file( $input->getArgument( 'file' ) ) );

		foreach ( $ids as $k => $id ) {
			$entityRevision = $apiEntityLookup->getEntity( $id );
			$item = $entityRevision->getItem();

			try {
				$statements = $item->getStatementGroupList()->getStatementGroup( 'P131' );
			} catch ( \Exception $ex ) {
				$error = "No P131 statements found on $id";
				$labels = $item->getLabels();

				if ( $labels->hasTerm( 'en' ) ) {
					$error .= " (" . $labels->getTerm( 'en' ) . ")";
				}

				$output->writeln( $error );

				continue;
			}

			$values = array();

			foreach ( $statements as $statement ) {
				$valueId = $statement->getMainSnak()->getDataValue()->getEntityId();

				if ( $valueId === $input->getArgument( 'to-remove' ) ) {
					$statementGuid = $statement->getGuid();
				}

				$values[] = $valueId;
			}

			if ( isset( $statementGuid ) ) {
				$res = $statementRemover->remove( $statementGuid, $entityRevision->getRevisionId() );
				$text = "Removed statement from $id";

				if ( $item->getLabels()->hasTerm( 'en' ) ) {
					$text .= " (" . $item->getLabels()->getTerm( 'en' ) . ")";
				}

				$output->writeln( $text );
				sleep( 2 );
			}
		}
	}

}

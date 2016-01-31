<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
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

			$itemFormatter = new ConsoleItemFormatter();
			$text = $itemFormatter->format(
				$item,
				$input->getArgument( 'lang' ),
				array( 'label', 'description' )
			);

			$statements = $item->getStatementGroupList()->getStatementGroup( 'P131' );
			$values = array();

			foreach ( $statements as $statement ) {
				$valueId = $statement->getMainSnak()->getDataValue()->getEntityId();

				if ( $valueId === 'Q1384' ) {
					$statementGuid = $statement->getGuid();
				}

				$values[] = $valueId;
			}

			if ( isset( $statementGuid ) ) {
				$output->writeln( $text );
				$output->writeln( "\nP131:\n" );
				$output->writeln( implode( "\n", $values ) . "\n" );

				$helper = $this->getHelper( 'question' );
				$question = new ConfirmationQuestion( '<question>Remove Q1384 value?</question> ', false );

				if ( !$helper->ask( $input, $output, $question ) ) {
					return;
				}

				$res = $statementRemover->remove( $statementGuid, $entityRevision->getRevisionId() );

				var_export( $res );
			}
		}
	}

}

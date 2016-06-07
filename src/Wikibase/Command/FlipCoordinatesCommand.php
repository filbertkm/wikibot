<?php

namespace Wikibot\Wikibase\Command;

use Asparagus\QueryBuilder;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wikibot\ApiClientFactory;
use Wikibot\Wikibase\ApiEntityLookup;
use Wikibot\Wikibase\DataModel\PropertyValueSnak;
use Wikibot\Wikibase\DataModel\StatementGroupList;
use Wikibot\Wikibase\Query\QueryRunner;
use Wikibot\Wikibase\Query\SparqlBuilder;
use Wikibot\Wikibase\WikibaseServices;

class FlipCoordinatesCommand extends Command {

	private $apiClientFactory;

	private $queryBuilder;

	private $queryRunner;

	private $statementListDeserializer;

	protected function configure() {
		$this->setName( 'flip-coords' )
			->setDescription( 'Flip coordinates' )
			->addArgument(
				'query',
				InputArgument::REQUIRED,
				'Query'
			);
	}

	public function setServices(
		ApiClientFactory $apiClientFactory,
		QueryBuilder $queryBuilder,
		QueryRunner $queryRunner
	) {
		$this->apiClientFactory = $apiClientFactory;
		$this->queryBuilder = $queryBuilder;
		$this->queryRunner = $queryRunner;

		$services = new WikibaseServices();
		$this->statementListDeserializer = $services
			->newDeserializerFactory()
			->newStatementListDeserializer();
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
        $sparqlBuilder = new SparqlBuilder( $this->queryBuilder );
		$sparqlBuilder->getPropertyEntityIdValueMultiMatches(
			explode( ',', $input->getArgument( 'query' ) )
		);

		$sparqlBuilder->setMinLon( 0 );
		$result = $this->queryRunner->doQuery( $sparqlBuilder->getQuery() );

		$apiClient = $this->apiClientFactory->newApiClient( 'wikidatawiki' );
		$apiEntityLookup = new ApiEntityLookup( $apiClient );

		$apiClient->login();

		foreach( $result->getItemIds() as $itemId ) {
			$entityRevision = $apiEntityLookup->getEntity( $itemId->getSerialization() );

			$statementGroups = $entityRevision->getItem()->getStatementGroupList();
			$statementGroup = $statementGroups->getStatementGroup( 'P625' );

			foreach ( $statementGroup as $statement ) {
				$mainSnak = $statement->getMainSnak();

				if ( $mainSnak instanceof PropertyValueSnak
					&& $mainSnak->getValueType() === 'globecoordinate'
				) {
					$value = $mainSnak->getValue();
					if ( $value['longitude'] > 0 ) {
						$question = new Question( "Flip " . $itemId->getSerialization() . '? ' );

						if ( $this->getHelper( 'question' )->ask( $input, $output, $question ) === 'y' ) {
							$value['longitude'] = -1 * abs( $value['longitude'] );

							$params = array(
								'action' => 'wbsetclaimvalue',
								'claim' => $statement->getGuid(),
								'snaktype' => 'value',
								'value' => json_encode( $value ),
								'baserevid' => $entityRevision->getRevisionId()
							);

							$res = $apiClient->post( $params );

							var_export( $res );
							echo "\n";
						}
					}
				}
			}
		}

		$output->writeln( 'done' );
	}

}

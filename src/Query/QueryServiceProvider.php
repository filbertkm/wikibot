<?php

namespace Wikibot\Query;

use Asparagus\QueryBuilder;
use Asparagus\QueryExecuter;
use Silex\Application;
use Silex\ServiceProviderInterface;

class QueryServiceProvider implements ServiceProviderInterface {

	public function register( Application $app ) {
		$app['query-builder'] = $app->share( function() use ( $app ) {
			return new QueryBuilder( $app['query.prefixes'] );
		} );

		$app['query-runner'] = $app->share( function() use ( $app ) {
			return new QueryRunner(
				new QueryExecuter( $app['query.url'] ),
				$app['query-builder']
			);
		} );
	}

	public function boot( Application $app ) {
		// @todo
	}

}

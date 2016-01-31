<?php

namespace Wikibot\Query;

use Asparagus\QueryExecuter;
use Silex\Application;
use Silex\ServiceProviderInterface;

class QueryServiceProvider implements ServiceProviderInterface {

	public function register( Application $app ) {
		$app['query-runner'] = $app->share( function() use ( $app ) {
			return new QueryRunner(
				new QueryExecuter( $app['query.url'] ),
				$app['query.prefixes']
			);
		} );
	}

	public function boot( Application $app ) {
		// @todo
	}

}

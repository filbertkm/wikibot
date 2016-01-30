<?php

namespace Wikibot\Query;

use Silex\Application;
use Silex\ServiceProviderInterface;

class QueryServiceProvider implements ServiceProviderInterface {

	public function register( Application $app ) {
		$app['query-runner'] = $app->share( function() use ( $app ) {
			return new QueryRunner(
				$app['query.prefixes'],
				$app['query.url']
			);
		} );
	}

	public function boot( Application $app ) {
		// @todo
	}

}

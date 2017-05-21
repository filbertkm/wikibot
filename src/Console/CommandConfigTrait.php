<?php

namespace Wikibot\Console;

use Symfony\Component\Console\Input\InputOption;

trait CommandConfigTrait {

	/**
	 * @param string $name
	 * @param string $description
	 */
	protected function defaultConfig( $name, $description ) {
        $this->setName( $name )
            ->setDescription( $description )
            ->addOption(
                'wiki',
                null,
                InputOption::VALUE_REQUIRED,
                'Wiki id',
                'devrepo'
            );

		return $this;
	}

}

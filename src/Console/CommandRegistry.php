<?php

namespace Wikibot\Console;

class CommandRegistry {

	public function getCommands() {
		$classNames = array(
			'\Wikibot\Console\Commands\AddStatementCommand'
		);

		foreach( $classNames as $className ) {
			$commands[] = $this->newCommand( $className );
		}

		return $commands;
	}

	private function newCommand( $class ) {
		$command = new $class();

		return $command;
	}

}

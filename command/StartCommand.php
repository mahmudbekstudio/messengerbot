<?php
class StartCommand extends Command {

	public function __construct($config, $text) {
		parent::__construct($config, $text);
	}

	public function run() {
		parent::run();

		//

		return $this->getResult();
	}

}
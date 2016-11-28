<?php
class StartCommand extends Command {

	public function __construct($config, $params) {
		parent::__construct($config, $params);
	}

	public function run() {
		parent::run();

		//

		return $this->getResult();
	}

}
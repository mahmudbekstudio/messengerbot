<?php
class StartCommand extends Command {

	public function __construct($navigation, $config, $params) {
		parent::__construct($navigation, $config, $params);
	}

	public function run() {
		parent::run();

		//

		return $this->getResult();
	}

}
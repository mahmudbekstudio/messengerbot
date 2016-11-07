<?php
class Command extends Instance {
	protected $config;
	protected $params;
	protected $keyBoard;
	private $result;

	public function __construct($config, $params) {
		$this->config = $config;
		$this->params = $params;
		$this->keyBoard = false;
	}

	public function setMessenger($messenger) {
		$this->messenger = $messenger;
	}

	public function run() {
		return $this->result;
	}

	public function notFound() {
		//
	}

	protected function getResult() {
		//TODO: generate result for show in messenger
		return $this->result;
	}

	protected function setKeyBoard($keyBoard) {
		$this->keyBoard = $keyBoard;
	}
}
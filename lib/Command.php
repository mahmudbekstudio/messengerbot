<?php
class Command extends Instance {
	protected $config;
	protected $text;
	protected $keyBoard;
	private $result;

	public function __construct($config, $text) {
		$this->config = $config;
		$this->text = $text;
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
<?php
abstract class Messenger extends Instance {

	protected $request;
	protected $params;
	public $text;
	public $command;
	public $commandPrefix = '';

	public function __construct($request) {
		$this->request = $request;
		$this->params = false;
		$this->text = $this->getText();
		$this->command = $this->isCommand() ? $this->getCommand() : '';
	}

	public static function getRequest() {
		return false;
	}

	public function getParams() {
		if($this->params === false) {
			$this->params = $this->generateParams($this->request);
		}

		return $this->params;
	}

	abstract public function userId();

	abstract public function generateParams($request);

	abstract public function render($result);

	public function isCommand() {
		return substr($this->text, 0, strlen($this->commandPrefix)) == $this->commandPrefix;
	}

	public function getCommand() {
		$result = $this->text;

		if($this->isCommand()) {
			$result = substr($result, strlen($this->commandPrefix));
		}

		return $result;
	}

	public function createCommand($text) {
		return $this->commandPrefix . $text;
	}

	abstract public function getText();

}
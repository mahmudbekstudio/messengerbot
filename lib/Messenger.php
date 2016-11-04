<?php
abstract class Messenger extends Instance {

	protected $params;

	public function __construct($params) {
		$this->params = $params;
	}

	public static function getParams() {
		return false;
	}

	abstract public function render($result);

}
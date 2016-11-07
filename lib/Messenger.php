<?php
abstract class Messenger extends Instance {

	protected $request;
	protected $params;

	public function __construct($request) {
		$this->request = $request;
		$this->params = false;
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

}
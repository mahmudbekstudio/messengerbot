<?php
class WebMessenger extends Messenger {

	public function __construct($params) {
		parent::__construct($params);
		$this->commandPrefix = '::';
		$this->init();
	}

	public static function getRequest() {
		return array('request' => $_REQUEST, 'get' => $_GET, 'post' => $_POST, 'files' => $_FILES);
	}

	public function userId() {
		return $this->request['request']['userId'];
	}

	public function generateParams($request) {
		$result = array('text' => '', 'file' => '');

		if(isset($request['request']['text'])) {
			$result['text'] = $request['request']['text'];
		}

		return $result;
	}

	public function render($result) {
		//
	}

	public function getText() {
		return $this->request['request']['text'];
	}

}
<?php
class WebMessenger extends Messenger {

	public function __construct($params) {
		parent::__construct($params);
	}

	public static function getRequest() {
		return array('get' => $_GET, 'post' => $_POST, 'files' => $_FILES);
	}

	public function userId() {
		return $this->request['get']['userId'];
	}

	public function generateParams($request) {
		//
	}

	public function render($result) {
		//
	}

}
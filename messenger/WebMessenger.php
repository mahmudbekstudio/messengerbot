<?php
class WebMessenger extends Messenger {

	public function __construct($params) {
		parent::__construct($params);
	}

	public static function getParams() {
		return array('get' => $_GET, 'post' => $_POST, 'files' => $_FILES);
	}

	public function render($result) {
		//
	}

}
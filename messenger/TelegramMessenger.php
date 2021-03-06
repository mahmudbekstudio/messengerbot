<?php
class TelegramMessenger extends Messenger {

	public function __construct($params) {
		parent::__construct($params);
		$this->commandPrefix = '>';
		$this->init();
	}

	public static function getRequest() {
		$content = file_get_contents("php://input");
		$update = json_decode($content, true);

		if (!$update) {
			exit;
		}

		return $update;
	}

	public function userId() {
		return 0;
	}

	public function generateParams($request) {
		//
	}

	public function render($result) {
		//
	}

	public function getText() {
		// TODO: Implement getText() method.
	}
}
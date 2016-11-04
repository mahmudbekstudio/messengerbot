<?php
class TelegramMessenger extends Messenger {

	public function __construct($params) {
		parent::__construct($params);
	}

	public static function getParams() {
		$content = file_get_contents("php://input");
		$update = json_decode($content, true);

		if (!$update) {
			exit;
		}

		return $update;
	}

	public function render($result) {
		//
	}
}
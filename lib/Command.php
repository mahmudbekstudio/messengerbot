<?php
class Command extends Instance {
	protected $config;
	protected $params;
	private $result;
	protected $messenger;

	public function __construct($config, $params) {
		$this->config = $config;
		$this->params = $params;
		$this->messenger = Application::get('messenger');
	}

	public function run() {
		return $this->result;
	}

	public function addMessage($message) {
		if(!isset($this->result['message'])) {
			$this->result['message'] = array();
		}

		$this->result['message'][] = $message;
	}

	public function notFound($text) {
		$this->result['notFound'] = $text;
	}

	public function addTopKeyboard($command) {
		$this->addCommand($command, 'topKeyboard');
	}

	public function bottomTopKeyboard($command) {
		$this->addCommand($command, 'bottomKeyboard');
	}

	public function addCommand($command, $keyboardType = 'keyboard') {
		if(!isset($this->result[$keyboardType])) {
			$this->result[$keyboardType] = array();
		}

		if(is_array($command)) {
			$this->result[$keyboardType] = array_merge($this->result[$keyboardType], $command);
		} else {
			$this->result[$keyboardType][] = $command;
		}
	}

	public function addFile($file, $fileType = 'file') {
		if(!isset($this->result['file'])) {
			$this->result['file'] = array();
		}

		$this->result['file'][] = array('file' => $file, 'fileType' => $fileType);
	}

	public function addImage($file) {
		$this->addFile($file, 'image');
	}

	public function getResult() {
		//TODO: generate result for show in messenger
		return $this->result;
	}
}
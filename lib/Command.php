<?php
class Command extends Instance {
	protected $navigation;
	protected $config;
	protected $params;
	private $result;
	protected $messenger;
	protected $userAction;
	protected $actionPrefix = 'action';

	public function __construct($navigation, $config, $params) {
		$this->navigation = $navigation;
		$this->config = $config;
		$this->params = $params;
		$this->messenger = Application::get('messenger');

		$user = Application::get('user');
		$this->userAction = json_decode($user['user_action']);
	}

	public function run() {
		if(isset($this->config['childrenAction'][$this->messenger->command]) && count($this->config['childrenAction'][$this->messenger->command]) > 0) {
			$this->callAction($this->messenger->command);
		}

		if(isset($this->config['children'])) {
			foreach($this->config['children'] as $key => $val) {
				$this->addCommand($key);
			}
		}

		if(isset($this->config['childrenAction'])) {
			foreach($this->config['childrenAction'] as $key => $val) {
				if(isset($val['toTop'])) {
					if($val['toTop']) {
						$this->addTopKeyboard($key);
					} else {
						$this->addBottomKeyboard($key);
					}
				}
			}
		}

		return $this->result;
	}

	public function callAction($action, $params = array()) {
		$result = false;

		if($this->hasAction($action)) {
			$result = call_user_func_array(array($this, $this->getAction($action)), $params);
		}

		$this->saveAction();

		return $result;
	}

	public function getAction($action) {
		return $this->actionPrefix . ucfirst($action);
	}

	public function hasAction($action) {
		return method_exists($this, $this->getAction($action));
	}

	public function saveAction() {
		$user = Application::getModel('User');
		$currentUser = Application::get('user');
		$user->saveUserAction($currentUser['user_id'], json_encode($this->userAction));
	}

	public function actionBack() {

		array_pop($this->userAction);
		$this->config = $this->getNavigation($this->userAction);

		return true;
	}

	public function actionHome() {
		$this->userAction = array();
		$this->config = $this->navigation;

		return true;
	}

	private function getNavigation($userActionRoute, $navigation = false) {
		$navigation = ($navigation === false) ? $this->navigation : $navigation;
		$result = array();

		if(!empty($userActionRoute)) {
			$firstAction = array_shift($userActionRoute);
			if(isset($navigation['children'][$firstAction->command])) {
				if(isset($navigation['command']) && !isset($navigation['children'][$firstAction->command]['command'])) {
					$navigation['children'][$firstAction->command]['command'] = $navigation['command'];
				}

				if(isset($navigation['childrenAction'])) {
					if(!isset($navigation['children'][$firstAction->command]['childrenAction'])) {
						$navigation['children'][$firstAction->command]['childrenAction'] = array();
					}

					$navigation['children'][$firstAction->command]['childrenAction'] = array_merge($navigation['childrenAction'], $navigation['children'][$firstAction->command]['childrenAction']);
				}

				$result = $this->getNavigation($userActionRoute, $navigation['children'][$firstAction->command]);
			}
		}

		if(empty($result)) {
			$result = $navigation;
		}

		return $result;
	}

	public function addMessage($message) {
		if(!isset($this->result['message'])) {
			$this->result['message'] = array();
		}

		$this->result['message'][] = Language::__($message);
	}

	public function notFound($text) {
		$this->result['notFound'] = Language::__($text);
	}

	public function addTopKeyboard($command) {
		$this->addCommand(Language::__($command), 'topKeyboard');
	}

	public function addBottomKeyboard($command) {
		$this->addCommand(Language::__($command), 'bottomKeyboard');
	}

	public function addCommand($command, $keyboardType = 'keyboard') {
		if(!isset($this->result[$keyboardType])) {
			$this->result[$keyboardType] = array();
		}

		if(is_array($command)) {
			foreach($command as $key => $val) {
				$command[$key] = Language::__($val);
			}
			$this->result[$keyboardType] = array_merge($this->result[$keyboardType], $command);
		} else {
			$this->result[$keyboardType][] = Language::__($command);
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
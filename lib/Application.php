<?php
class Application extends Instance {

	private static $vars;
	private static $config;
	private static $model;
	private static $command;
	private static $navigation;

	private function __construct() {
		//
	}

	public static function run($config) {
		self::init($config);

		$messenger = self::get('messenger');
		$userId = $messenger->userId();
		$user = self::getUser($userId);
		$userParams = json_decode($user['additional_params']);

		Language::setLang($userParams->language);
		$userAction = self::getUserAction($user['user_action'], $messenger);
		$userCurrentNavigation = self::getCurrentNavigation($userAction);

		$command = $userCurrentNavigation['command'];
		$commandInstance = self::getCommand($command, $userCurrentNavigation, $messenger->getParams());
		$commandResult = $commandInstance->run();

		$messenger->render($commandResult);
	}

	public static function init($config) {
		self::setConfig($config);

		$dbConfig = self::getConfig('db');
		self::set('db', new Database($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['db'], $dbConfig['prefix'], $dbConfig['charset']));

		Language::init(self::getConfig('language'));

		$messenger = self::getConfig('messenger') . 'Messenger';
		include MESSENGER_PATH . '/' . $messenger . '.php';
		self::set('messenger', new $messenger($messenger::getRequest()));
	}

	public static function getNavigation() {
		if(isset(self::$navigation)) {
			return self::$navigation;
		}

		self::$navigation = include BASE_PATH . "/navigation.php";

		return self::$navigation;
	}

	public static function getUserAction($userAction, $messenger) {
		$userAction = json_decode($userAction);

		if($messenger->isCommand()) {
			$std = new stdClass();
			$std->command = $messenger->getCommand();
			$userAction[] = $std;
		}

		return $userAction;
	}

	public static function getCurrentNavigation($userActionRoute, $navigation = false) {
		$navigation = ($navigation === false) ? self::getNavigation() : $navigation;
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

				$result = self::getCurrentNavigation($userActionRoute, $navigation['children'][$firstAction->command]);
			}
		}

		if(empty($result)) {
			$result = $navigation;
		}

		return $result;
	}

	public static function getUser($userId) {
		$user = self::getModel('User');
		$messenger = self::getConfig('messenger');
		return $user->getUser($userId, $messenger);
	}

	public static function setCommand($commandName, $commandInstance) {
		self::$command[$commandName] = $commandInstance;
	}

	public static function getCommand($command, $userCurrentNavigation, $messengerParams) {
	    if(isset(self::$command[$command])) {
		    return self::$command[$command];
	    }

		$commandClass = $command . 'Command';
		$commandPath = COMMAND_PATH . '/' . $commandClass . '.php';
		if(file_exists($commandPath)) {
			include $commandPath;
			$commandInstance = new $commandClass($userCurrentNavigation, $messengerParams);
			self::setCommand($commandClass, $commandInstance);
			return $commandInstance;
		}

		return false;
	}

	protected static function setModel($name, $model) {
		self::$model[$name] = $model;
	}

	public static function getModel($model) {
		if(isset(self::$model[$model])) {
			return self::$model[$model];
		}

		$modelPath = MODEL_PATH . '/' . $model . '.php';
		if(file_exists($modelPath)) {
			include $modelPath;
			$modelInstance = new $model(self::get('db'));
			self::setModel($model, $modelInstance);
			return $modelInstance;
		}

		return false;
	}

	public static function setConfig($config) {
		self::$config = $config;
	}

	public static function getConfig($var) {
		return isset(self::$config[$var]) ? self::$config[$var] : false;;
	}

	public static function set($var, $val) {
		self::$vars[$var] = $val;
	}

	public static function get($var) {
		return isset(self::$vars[$var]) ? self::$vars[$var] : false;
	}

}
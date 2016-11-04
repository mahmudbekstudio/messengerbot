<?php
class Application extends Instance {

	private static $vars;
	private static $config;
	private static $model;

	private function __construct() {
		//
	}

	public static function run() {
		//
	}

	public static function init($config) {
		self::setConfig($config);

		$dbConfig = self::getConfig('db');
		self::set('db', new Database($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['db'], $dbConfig['prefix'], $dbConfig['charset']));

		Language::init(self::getConfig('language'));

		$messenger = self::getConfig('messenger') . 'Messenger';
		include MESSENGER_PATH . '/' . $messenger . '.php';
		self::set('messenger', new $messenger($messenger::getParams()));
	}

	protected static function setModel($name, $model) {
		self::$model[$model] = $model;
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
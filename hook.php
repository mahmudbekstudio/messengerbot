<?php
/*$arr = array(
	array('command' => 'test', 'params' => array('a' => 1, 'b' => 2)),
	array('command' => 'asd')
);
echo json_encode($arr);exit;*/
define('BASE_PATH', dirname(__FILE__));
define('LIB_PATH', BASE_PATH . '/lib');
define('COMMAND_PATH', BASE_PATH . '/command');
define('LANGUAGE_PATH', BASE_PATH . '/language');
define('MESSENGER_PATH', BASE_PATH . '/messenger');
define('MODEL_PATH', BASE_PATH . '/model');

require_once LIB_PATH . '/Instance.php';
require_once LIB_PATH . '/Application.php';
require_once LIB_PATH . '/Command.php';
require_once LIB_PATH . '/Database.php';
require_once LIB_PATH . '/Language.php';
require_once LIB_PATH . '/Messenger.php';
require_once LIB_PATH . '/Model.php';

$config = require_once BASE_PATH . '/config.php';

Application::run($config);
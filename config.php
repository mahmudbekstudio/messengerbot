<?php
return array(
	'navigation' => array(
		'command' => 'Start',//
		'childrenAction' => array(
			Language::__('Back') => array('toTop' => true),
		),
		'children' => array(
			Language::__('Search') => array(
				'command' => 'Search',
				/*'childrenAction' => array(
					//Language::__('Search Text') => array('toTop' => true),
				),
				'children' => array(),*/
			),
			Language::__('Add') => array(
				//
			),
			Language::__('Settings') => array(),
			Language::__('Contacts') =>array(),
		)
	),
	'language' => array(
		'language' => 'en',
		'enabled_languages' => array('en', 'ru', 'uz'),
		'language_name' => array('English', 'Русский', 'O`zbek')
	),
	'db' => array(
		'host'=> 'localhost',
		'db' => 'telegram',
		'user' => 'root',
		'pass' => '',
		'prefix' => 'telegram_',
		'charset' => 'utf8'
	),
	'params' => array(
		'adminEmail' => 'mahmudbekstudio@mail.ru',
		'adminPhone' => '998903231755',
		'adminName' => 'Makhmud Adilov'
	),
	'messenger' => 'Web',
	'env_dev' => true,
	'debug' => true,
);
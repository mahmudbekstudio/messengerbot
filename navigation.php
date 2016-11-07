<?php
return array(
	'command' => 'Start',//
	'childrenAction' => array(
		Language::__('Back') => array('toTop' => true),
	),
	'children' => array(
		Language::__('Category') => array(
			'command' => 'Category',

		),
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
		'test' => array(
			'children' => array(
				'test1' => array(),
				'test2' => array(
					'chidlren' => array(
						'test21' => array(),
						'test22' => array(),
					)
				),
			)
		)
	)
);
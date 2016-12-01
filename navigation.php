<?php
return array(
	'command' => 'Start',//
	'childrenAction' => array(
		'Back' => array('toTop' => true),
	),
	'children' => array(
		'Category' => array(
			'command' => 'Category',

		),
		'Search' => array(
			'command' => 'Search',
			/*'childrenAction' => array(
				//'Search Text' => array('toTop' => true),
			),
			'children' => array(),*/
		),
		'Add' => array(
			//
		),
		'Settings' => array(),
		'Contacts' =>array(),
		'test' => array(
			'children' => array(
				'test1' => array(),
				'test2' => array(
					'children' => array(
						'test21' => array(
							'children' => array(
								'aaa' => array()
							)
						),
						'test22' => array(),
					)
				),
			)
		)
	)
);
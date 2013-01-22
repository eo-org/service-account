<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'adminrest-org'		=> 'Rest\Controller\OrgController',
			'adminrest-user'	=> 'Rest\Controller\UserController',
			'adminrest-site'	=> 'Rest\Controller\SiteController',
		)
	),
	'router' => array(
		'routes' => array(
			'adminrest' => array(
				'type' => 'literal',
    			'options' => array(
    				'route' => '/adminrest'
    			),
    			'may_terminate' => true,
    			'child_routes' => array(
    				'adminrest-childroutes' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '[/:controller].[:format][/:id]',
							'constraints' => array(
								'controller' => '[a-z-]*',
								'format' => '(json|html)',
								'id' => '[a-z0-9]*'
							)
						),
					),
				)
			),
		),
	),
);
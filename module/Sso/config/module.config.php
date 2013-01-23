<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'sso-index'		=> 'Sso\Controller\IndexController',
		)
	),
	'router' => array(
		'routes' => array(
			'sso' => array(
				'type' => 'segment',
    			'options' => array(
    				'route' => '/sso[/:action][.:format]',
    				'constraints' => array(
    					'action' => '[a-z-]*',
    					'format' => '(json|xml)'
    				),
    				'defaults' => array(
    					'controller' => 'sso-index',
    					'action' => 'index',
    					'format' => ''
    				)
    			),
    			'may_terminate' => true,
			),
		),
	),
	'view_manager' => array(
		'template_map' => array(
			'sso/index/index'	=> __DIR__ . '/../view/sso/index/index.phtml',
			'sso/index/login'	=> __DIR__ . '/../view/sso/index/login.phtml',
			'sso/index/info'	=> __DIR__ . '/../view/sso/index/info.xml.phtml',
		)
	),
);
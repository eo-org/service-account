<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'admin-index' 	=> 'Admin\Controller\IndexController',
        	'admin-org'		=> 'Admin\Controller\OrgController',
        	'admin-site'	=> 'Admin\Controller\SiteController',
        	'admin-user'	=> 'Admin\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller'    => 'admin-index',
                        'action'        => 'index',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            		'actionroutes' => array(
    					'type' => 'segment',
		                'options' => array(
		                    'route' => '[/:controller][/:action]',
		                    'constraints' => array(
		    					'controller' => '[a-z-]*',
		                        'action' => '[a-z-]*'
		                    ),
		                ),
						'child_routes' => array(
							'wildcard' => array(
								'type' => 'wildcard',
							),
						),
    				),
            	)
            ),
        ),
    ),
	'view_manager' => array(
		'template_path_stack' => array(
			'admin' => __DIR__ . '/../view',
		),
	),
	'admin_toolbar' => array(
		'org' => array(
			'title' => '机构管理',
			'url' => '/admin/admin-org/',
		),
	)
);
<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'index' => 'Application\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'application' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller'    => 'index',
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
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
    		'layout/error'				=> __DIR__ . '/../view/layout/error.phtml',
            'error/404'					=> __DIR__ . '/../view/error/404.phtml',
            'error/index'				=> __DIR__ . '/../view/error/index.phtml',
        	'layout/layout'				=> __DIR__ . '/../view/layout/layout.phtml',
        	'layout/admin'				=> __DIR__ . '/../view/layout/layout-admin.phtml',
        	'application/index/index'	=> __DIR__ . '/../view/application/index/index.phtml',
        	'application/site/index'	=> __DIR__ . '/../view/application/site/index.phtml',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy'
    	),
    ),
	'service_manager' => array(
		'factories' => array('ConfigObject\EnvironmentConfig' => function($serviceManager) {
			$siteConfig = new Application\EnvConfig(include 'config/env.config.php');
			return $siteConfig;
		})
	),
	'controller_plugins' => array(
		'invokables' => array(
			'brickConfig'		=> 'Brick\Helper\Controller\Config',
			'documentManager'	=> 'Core\Controller\Plugin\DocumentManager',
			'formatData'		=> 'Core\Controller\Plugin\FormatData',
			'envConfig'			=> 'Core\Controller\Plugin\EnvConfig',
		)
	),
	'view_helpers' => array(
		'invokables' => array(
			'singleForm'			=> 'Core\View\Helper\SingleForm',
			'tabForm'				=> 'Core\View\Helper\TabForm',
			'bootstrapRow'			=> 'Core\View\Helper\BootstrapRow',
			'bootstrapCollection'	=> 'Core\View\Helper\BootstrapCollection',
			'selectOptions'			=> 'Core\View\Helper\SelectOptions',
			'envConfig'				=> 'Core\View\Helper\EnvConfig',
		),
	),
);
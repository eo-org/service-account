<?php
namespace Admin;

use Zend\EventManager\StaticEventManager, Zend\Mvc\MvcEvent;
use Core\Brick\Register, Admin\RegisterConfig;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'initLayout'), -10);
	}
	
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
            ),
        );
    }
    
    public function initLayout(MvcEvent $e)
    {
    	$rm = $e->getRouteMatch();
    	$controller = $e->getTarget();
    	$controllerName = $controller->params()->fromRoute('controller');
    	$format = $controller->params()->fromRoute('format');
    	if($format == 'ajax') {
    		$controller->layout('layout-admin/ajax');
    	} else if($format == 'bone') {
    		$controller->layout('layout-admin/bone');
    	} else {
    		$controller->layout('layout/admin');
    	}
    	
    	$routeMatch = $e->getRouteMatch();
    	$brickRegister = new Register($controller, new RegisterConfig());
    	$jsList = $brickRegister->getJsList();
    	$cssList = $brickRegister->getCssList();
    	$brickViewList = $brickRegister->renderAll();
    	
    	$viewModel = $e->getViewModel();
    	$viewModel->setVariables(array(
    		'routeMatch'	=> $routeMatch,
    		'brickViewList'	=> $brickViewList,
    		'jsList'		=> $jsList,
    		'cssList'		=> $cssList,
    	));
    }
}
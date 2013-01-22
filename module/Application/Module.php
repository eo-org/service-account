<?php
namespace Application;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\StaticEventManager, Zend\EventManager\EventInterface, Zend\Mvc\MvcEvent;
use Brick\Module\TwigView;
use Brick\Helper\Twig\Filter as TwigFilter;
class Module implements BootstrapListenerInterface
{
	public function onBootstrap(EventInterface $event)
	{
		$application = $event->getTarget();
		$sm = $application->getServiceManager();
		
		TwigFilter::setServiceManager($sm);
		$twigEnv = TwigView::getTwigEnv();
		$twigEnv->addFilter('outputImage',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::outputImage'));
		$twigEnv->addFilter('graphicDataJson',	new \Twig_Filter_Function('Brick\Helper\Twig\Filter::graphicDataJson'));
		$twigEnv->addFilter('substr',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::substr'));
		$twigEnv->addFilter('url',				new \Twig_Filter_Function('Brick\Helper\Twig\Filter::url'));
		$twigEnv->addFilter('pageLink',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::pageLink'));
		$twigEnv->addFilter('translate',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::translate'));
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
}
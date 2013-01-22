<?php
namespace DoctrineMongo;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Doctrine\Common\Persistence\PersistentObject,
Doctrine\ODM\MongoDB\DocumentManager,
Doctrine\ODM\MongoDB\Configuration,
Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
Doctrine\MongoDB\Connection;

class Module implements BootstrapListenerInterface
{
	public function onBootstrap(EventInterface $event)
	{
		AnnotationDriver::registerAnnotationClasses();
		$config = new Configuration();
		$config->setDefaultDB('service_account');
		
		$config->setProxyDir(BASE_PATH . '/service-account/doctrineCache');
		$config->setProxyNamespace('DoctrineMongoProxy');
		$config->setHydratorDir(BASE_PATH . '/service-account/doctrineCache');
		$config->setHydratorNamespace('DoctrineMongoHydrator');
		$config->setMetadataDriverImpl(AnnotationDriver::create(BASE_PATH . '/service-account/doctrineCache/class'));
		
		$config->setAutoGenerateHydratorClasses(true);
		$config->setAutoGenerateProxyClasses(true);
		
		$dm = DocumentManager::create(new Connection(), $config);
		PersistentObject::setObjectManager($dm);
		
		$application = $event->getTarget();
		$sm = $application->getServiceManager();
		$sm->setService('DocumentManager', $dm);
	}
}
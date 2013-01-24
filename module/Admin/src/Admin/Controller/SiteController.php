<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\User\EditForm;
use Account\Document\Org, Account\Document\Site;

class SiteController extends AbstractActionController
{
	public $actionTitle;
	public $actionMenu;
	
	public function indexAction()
	{
		$orgId = $this->params('orgCode');
		$dm = $this->documentManager();
		$doc = $dm->getRepository('Account\Document\Org')->find($orgId);
		if(is_null($doc)) {
			throw new \Exception('org not found');
		}
		$this->actionTitle = $doc->getOrgName()." [网站列表] ";
		$this->actionMenu = array('create');
		return array(
			'orgCode' => $orgId
		);
	}
	
	public function createAction()
	{
		$orgCode = $this->params('orgCode');
		$dm = $this->documentManager();
		
		$orgDoc = $dm->getRepository('Account\Document\Org')->find($orgCode);
		if(is_null($orgDoc)) {
			throw new \Exception('org not found!');
		}
		
		$counter = $dm->getRepository('Account\Document\Counter')->findOneByName('remote_site');
		$newGlobalSiteId = $counter->getValue() + 1;
		$counter->setValue($newGlobalSiteId);
		$dm->persist($counter);
		
		$domainDoc = new \Account\Document\Domain();
		$domainDoc->setFromArray(array(
			'domainName' => $newGlobalSiteId.'.ant.fucms.com',
			'isActive' => true,
			'isDefault' => true,
		));
		$siteDoc = new Site();
		$siteDoc->setFromArray(array(
			'organizationCode' => $orgCode,
			'globalSiteId' => $newGlobalSiteId,
			'active' => true,
		));
		$siteDoc->addDomain($domainDoc);
		$dm->persist($siteDoc);
		$dm->flush();
		$this->flashMessenger()->addMessage('新网站已创建');
		return $this->redirect()->toRoute(
			'admin/actionroutes/wildcard',
			array('action' => 'index', 'controller' => 'admin-site', 'orgCode' => $orgCode)
		);
	}
	
	public function editAction()
	{
		$id = $this->params('id');
		$dm = $this->documentManager();
		
		$doc = $dm->getRepository('Account\Document\Org')->find($id);
		if(is_null($doc)) {
			$doc = new Org();
		}
		
		$form = new EditForm();
		$form->setData($doc->toArray());
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
				$doc->setFromArray($form->getData());
				$dm->persist($doc);
				$dm->flush();
				$this->flashMessenger()->addMessage('机构信息 已经成功保存');
				return $this->redirect()->toRoute('admin/actionroutes/wildcard', array('action' => 'index', 'controller' => 'admin-org'));
			}
		}
		
		$this->actionTitle = "机构管理";
		$this->actionMenu = array('save');
		
		return array(
			'form' => $form
		);
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$ro = App_Factory::_m('RemoteOrganization');
		$roDoc = $ro->find($id);
		$roDoc->isActive = false;
		$roDoc->save();
		$this->_helper->redirector->gotoSimple('index');
	}
}
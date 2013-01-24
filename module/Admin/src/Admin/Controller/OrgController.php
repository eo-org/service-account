<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\Org\EditForm;
use Account\Document\Org;

class OrgController extends AbstractActionController
{
	public $actionTitle;
	public $actionMenu;
	
	public function indexAction()
	{
		$this->actionTitle = "机构管理";
		$this->actionMenu = array('create');
	}
	
	public function createAction()
	{
		$dm = $this->documentManager();
		$doc = new Org();
		
		$form = new EditForm();
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
			$form->setData($postData);
			if($form->isValid()) {
				$doc->setFromArray($form->getData());
				$dm->persist($doc);
				$dm->flush();
				$this->flashMessenger()->addMessage('机构信息已经成功保存');
				return $this->redirect()->toRoute('admin/actionroutes/wildcard', array('action' => 'index', 'controller' => 'admin-org'));
			}
		}
		
		$this->actionTitle = "机构管理";
		$this->actionMenu = array('save');
		
		return array(
			'form' => $form
		);
	}
	
	public function editAction()
	{
		$id = $this->params('id');
		$dm = $this->documentManager();
		
		$doc = $dm->getRepository('Account\Document\Org')->find($id);
		if(is_null($doc)) {
			throw new \Exception('org not found');
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
		
		$userDocs = $dm->getRepository('Account\Document\User')->findByOrgCode($id);
		$siteDocs = $dm->getRepository('Account\Document\Site')->findByOrganizationCode($id);
		
		return array(
			'userDocs' => $userDocs,
			'siteDocs' => $siteDocs,
			'form' => $form,
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
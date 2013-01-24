<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\User\EditForm;
use Account\Document\Org, Account\Document\User;

class UserController extends AbstractActionController
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
		$this->actionTitle = $doc->getOrgName()." [用户列表] ";
		$this->actionMenu = array('create');
		return array(
			'orgCode' => $orgId
		);
	}
	
	public function createAction()
	{
		$orgCode = $this->params('orgCode');
		if(empty($orgCode)) {
			throw new \Exception('org code is empty!');
		}
		$form = new EditForm();
		if($this->getRequest()->isPost()) {
			$user = new User();
			$postData = $this->getRequest()->getPost();
			$postData['orgCode'] = $orgCode;
			$postData['userType'] = 'website-admin';
			$postData['password'] = rand(111111, 999999);
			$form->setInputFilter($user->getInputFilter());
			$form->setData($postData);
			if($form->isValid()) {
				$validData = $form->getData();
				$user->exchangeArray($validData);
				$dm = $this->documentManager();
				$dm->persist($user);
				$dm->flush();
				return $this->redirect()->toRoute(
					'admin/actionroutes/wildcard',
					array('action' => 'index', 'controller' => 'admin-user', 'orgCode' => $orgCode)
				);
			} else {
				$errorMsg = $form->getMessages();
			}
		}
		
		$this->actionTitle = "添加新用户";
		$this->actionMenu = array('save');
		return array(
			'form' => $form
		);
	}
	
	public function editAction()
	{
		$id = $this->params('id');
		if(empty($id)) {
			throw new \Exception('user id is empty!');
		}
		
		$dm = $this->documentManager();
		$user = $dm->getRepository('Account\Document\User')->findOneById($id);
		if(empty($user)) {
			throw new \Exception('user not found!');
		}
		$orgCode = $user->getOrgCode();
		 
		$form = new EditForm();
		$oldData = $user->getArrayCopy();
		$form->setData($oldData);
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
			$form->setInputFilter($user->getInputFilter());
			$oldData['loginName'] = $postData['loginName'];
			$form->setData($oldData);
			if($form->isValid()) {
				$validData = $form->getData();
				$user->exchangeArray($validData);
				$dm->persist($user);
				$dm->flush();
				return $this->redirect()->toRoute(
					'admin/actionroutes/wildcard',
					array('action' => 'index', 'controller' => 'admin-user', 'orgCode' => $orgCode)
				);
			} else {
				$errorMsg = $form->getMessages();
			}
		}
		
		$this->actionTitle = "修改用户设定";
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
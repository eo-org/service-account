<?php
namespace Sso\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel, Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Sso\Form\Index\LoginForm, Sso\Validator, Sso\RemoteUserCookie;
use Account\Document\Token;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$csr = new RemoteUserCookie();
    	if($csr->isLogin()) {
    		$userId = $csr->getUserId();
    		$userData = $csr->getUserData();
    		return array(
    			'userId' => $userId,
    			'userData' => $userData
    		);
    	} else {
    		return array(
    			'userId' => null,
    			'userData' => null
    		);
    	}
    }
    
    public function loginAction()
    {	
    	$consumer = $this->params()->fromQuery('consumer');
    	$ret = $this->params()->fromQuery('ret');
    	$timeStamp = $this->params()->fromQuery('timeStamp');
    	$token = $this->params()->fromQuery('token');
    	$sig = $this->params()->fromQuery('sig');
    	
    	if(empty($ret) || empty($consumer) || empty($token)) {
    		throw new \Exception('login format error');
    	}
    	
    	$result = Validator::validateLoginUrl($consumer, $ret, $timeStamp, $token, $sig);
    	
    	if($result != 'success') {
    		switch($result) {
    			case 'timeout':
    				throw new \Exception('Request Timeout');
    		}
    		throw new Exception('Sig Error');
    	}
    	
    	$dm = $this->documentManager();
    	$csr = new RemoteUserCookie();
    	if($csr->isLogin()) {
    		$newToken = new Token();
    		$newToken->setFromArray(array(
    			"token" => $token,
    			"userId" => $csr->getUserId(),
    			"userData" => $csr->getUserData()
    		));
    		$dm->persist($newToken);
    		$dm->flush();
    		header("Location: ".$ret);
    		exit(0);
    	}
    	
    	$form = new LoginForm();
    	$errorMsg = array();
    	if($this->getRequest()->isPost()) {
    		$postData = $this->getRequest()->getPost();
    		$form->setData($postData);
    		if($form->isValid()) {
	    		$cookieData = $csr->login($form->getData(), $dm);
	    		if($cookieData === false) {
	    			$errorMsg[] = "用户密码错误";
	    		} else {
	    			$newToken = new Token();
		    		$newToken->setFromArray(array(
		    			"token" => $token,
		    			"userId" => $cookieData['userId'],
		    			"userData" => $cookieData['userData']
		    		));
		    		$dm->persist($newToken);
		    		$dm->flush();
	    			header("Location: ".$ret);
	    			exit(0);
	    		}
    		}
    	}
    	
    	return array(
    		'form' => $form,
    		'errorMsg' => $errorMsg
    	);
    }
    
    public function infoAction()
    {
    	$token = $this->params()->fromPost('token');
		if(empty($token)) {
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'fail');
			$this->getResponse()->setStatusCode(403);
			return array(
				'userId' => null,
				'userData' => null
			);
		}
		$dm = $this->documentManager();
		$tokenDoc = $dm->getRepository('Account\Document\Token')->findOneByToken($token);
		if(!is_null($tokenDoc)) {
			$userId = $tokenDoc->getUserId();
			$userData = $tokenDoc->getUserData();
			$dm->remove($tokenDoc);
			$dm->flush();
			$viewModel = new ViewModel(array(
				'userId' => $userId,
				'userData' => Json::decode($userData)
			));
			$viewModel->setTerminal(true);
			return $viewModel;
		} else {
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'fail');
			$this->getResponse()->setStatusCode(403);
			return array();
		}
    }
    
    public function siteInfoAction()
    {
    	$siteId = $this->params()->fromQuery('siteId');
    	
    	$dm = $this->documentManager();
    	$doc = $dm->getRepository('Account\Document\Site')->findOneById($siteId);
    	if(is_null($doc)) {
    		$doc = $dm->getRepository('Account\Document\Site')->findOneByRemoteSiteId($siteId);
    	}
    	$result = array();
    	if(is_null($doc)) {
    		$result['errMsg'] = 'site not found with id'. $siteId;
    		$result['result'] = 'fail';
    	} else {
    		$result['data'] = $doc->toArray();
    		$result['result'] = 'success';
    	}
    	return new JsonModel($result);
    }
}

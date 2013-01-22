<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
	public $actionTitle;
	public $actionMenu;
	
    public function indexAction()
    {
    	$this->actionMenu = array();
    	$this->actionTitle = '机构和用户管理系统';
    }
}

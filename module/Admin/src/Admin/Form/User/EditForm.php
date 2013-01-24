<?php
namespace Admin\Form\User;

use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct()
    {
    	parent::__construct('user-edit');
    	
    	$this->add(array(
    		'name' => 'loginName',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => '用户登录邮箱')
    	));
    }
}
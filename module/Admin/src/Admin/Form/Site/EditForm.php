<?php
namespace Admin\Form\Site;

use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct()
    {
    	parent::__construct('site-edit');
    	
    	$this->add(array(
    		'name' => 'orgName',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => '网站名')
    	));
    }
    
    public function getTabSettings()
    {
    	return array(
    		array('handleLabel' => '基本信息', 'content' => array('orgName')),
    	);
    }
}
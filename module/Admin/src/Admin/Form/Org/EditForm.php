<?php
namespace Admin\Form\Org;

use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct()
    {
    	parent::__construct('org-edit');
    	
    	$this->add(array(
    		'name' => 'orgName',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => '机构名')
    	));
    }
    
    public function getTabSettings()
    {
    	return array(
    		array('handleLabel' => '基本信息', 'content' => array('orgName')),
    	);
    }
}
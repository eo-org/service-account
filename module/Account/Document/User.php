<?php
namespace Account\Document;

use Zend\InputFilter\Factory as FilterFactory, Zend\InputFilter\InputFilter;
use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="remote_user"
 * )
 * 
 * */
class User extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $orgCode;
	
	/** @ODM\Field(type="string")  */
	protected $userType;
	
	/** @ODM\Field(type="string")  */
	protected $loginName;
	
	/** @ODM\Field(type="string")  */
	protected $password;
	
	protected $inputFilter;
	
	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$inputFactory = new FilterFactory();
				
			$inputFilter->add($inputFactory->createInput(array(
				'name'		=> 'orgCode',
				'requried'	=> true,
				'filters'	=> array(
					array('name' => 'StringTrim')
				),
			)));
			$inputFilter->add($inputFactory->createInput(array(
				'name'		=> 'userType',
				'requried'	=> true,
				'filters'	=> array(
					array('name' => 'StringTrim')
				)
			)));
			
			$dm = $this->getObjectManager();
			$inputFilter->add($inputFactory->createInput(array(
				'name'		=> 'loginName',
				'requried'	=> true,
				'filters'	=> array(
					array('name' => 'StringTrim')
				),
				'validators' => array(
					array('name' => 'EmailAddress'),
					new \Core\Validator\InDb(
						array(
							'dm' => $dm,
							'repository' => 'Account\Document\User',
							'field' => 'LoginName',
							'excludeId' => $this->id
						)
					)
				)
			)));
			$inputFilter->add($inputFactory->createInput(array(
				'name'		=> 'password',
				'requried'	=> true,
				'filters'	=> array(
					array('name' => 'StringTrim')
				),
			)));
			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
	
	public function exchangeArray($data)
	{
		$this->orgCode = $data['orgCode'];
		$this->userType = $data['userType'];
		$this->loginName = $data['loginName'];
		$this->password  = $data['password'];
	}
	
	public function getArrayCopy()
	{
		return array(
			'id' => $this->id,
			'orgCode' => $this->orgCode,
			'userType' => $this->userType,
			'loginName'	=> $this->loginName,
			'password' => $this->password
		);
	}
}
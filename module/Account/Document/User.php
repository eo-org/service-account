<?php
namespace Account\Document;

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
	protected $loginName;
	
	/** @ODM\Field(type="string")  */
	protected $password;
}
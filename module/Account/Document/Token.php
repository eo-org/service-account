<?php
namespace Account\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="token"
 * )
 * 
 * */
class Token extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $token;
	
	/** @ODM\Field(type="string")  */
	protected $userId;
	
	/** @ODM\Field(type="string")  */
	protected $userData;
}
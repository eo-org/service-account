<?php
namespace Account\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="counter"
 * )
 * 
 * */
class Counter extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $name;
	
	/** @ODM\Field(type="int")  */
	protected $value;
}
<?php
namespace Account\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="remote_organization"
 * )
 * 
 * */
class Org extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $orgName;
}
<?php
namespace Account\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs as LEA;

/** 
 * @ODM\Document(
 * 		collection="site"
 * )
 * 
 * */
class Site extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $organizationCode;

	/** @ODM\Field(type="string")  */
	protected $remoteSiteId;
	
	/** @ODM\Field(type="string")  */
	protected $globalSiteId;
	
	/** @ODM\EmbedMany(targetDocument="Account\Document\Domain")  */
	protected $domains = array();
	
	/** @ODM\Field(type="boolean")  */
	protected $active;
	
	public function addDomain($domainDocument)
	{
		$this->domains[] = $domainDocument;
		return $this;
	}
	
	public function removeDomain($id)
	{
		foreach($this->domains as $key => $domainDoc) {
			if($domainDoc->getId() == $id) {
				if($domainDoc->getIsDefault()) {
					return false;
				} else {
					unset($this->domains[$key]);
					return true;
				}
			}
		}
		return false;
	}
}
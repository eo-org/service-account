<?php
namespace Rest\Controller;

use MongoRegex;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractRestfulController
{
	public function getList()
	{
		$orgCode = $this->getRequest()->getHeader('X-Org-Code')->getFieldValue();
		
		$filter = $this->getRequest()->getQuery();
		
		$currentPage = $filter['page'];
		$sIndex = $filter['sIndex'];
		$sOrder = intval($filter['sOrder']);
		$queryStr = $filter['query'];
		
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		$pageSize = 20;
		$skip = $pageSize * ($currentPage - 1);
		
		$dm = $this->documentManager();
		$qb = $dm->createQueryBuilder('Account\Document\User');
		$qb->field('orgCode')->equals($orgCode);
		if($queryStr != 'none') {
			$queryArr = explode('-', $queryStr);
			foreach($queryArr as $qItem) {
				list($key, $val) = explode(':', $qItem);
				switch($key) {
					case 'orgName':
						$qb->field($key)->equals(new MongoRegex("/".$val."/"));
						break;
				}
			}
		}
		$cursor = $qb->limit($pageSize)->skip($skip)
			->sort($sIndex, $sOrder)
			->hydrate(false)
			->getQuery()
			->execute();
		$data = $this->formatData($cursor);
		$dataSize = $qb->getQuery()->execute()->count();
		
		$result = array();
		$result['data'] = $data;
		$result['dataSize'] = $dataSize;
		$result['pageSize'] = $pageSize;
		$result['currentPage'] = $currentPage;
		
		return $result;
	}
	
	public function get($id)
	{
	
	}
	
	public function create($data)
	{
	
	}
	
	public function update($id, $data)
	{
		
	}
	
	public function delete($id)
	{
		$factory = $this->dbFactory();
		$co = $factory->_m('Article');
		$doc = $co->find($id);
		$doc->toggleTrash();
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
	}
}
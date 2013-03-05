<?php
namespace Sso;

use Zend\Json\Json;

class RemoteUserCookie
{
	private static $_md5salt = 'fie&4Jgoaaq1d#$@(lj21';
	private static $_md5salt2 = '6234GY69)+3jo108';
	
	public function login($post, $dm)
	{
		$loginName = $post['loginName'];
		$password = $post['password'];
		
		$userDoc = $dm->createQueryBuilder('Account\Document\User')
			->field('loginName')->equals($loginName)
			->field('password')->equals($password)
			->getQuery()
			->getSingleResult();
		
		if(!is_null($userDoc)) {
			$userId = $userDoc->getId();
			$userType = $userDoc->getUserType();
			$orgCode = $userDoc->getOrgCode();
			$loginName = $userDoc->getLoginName();
			$siteIds = array();
			$siteDocs = $dm->getRepository('Account\Document\Site')->findByOrganizationCode($orgCode);
			foreach($siteDocs as $siteDoc) {
				$siteIds[] = $siteDoc->getId();
			}
			
			$startTimeStamp = time();
			$userData = Json::encode(array(
				'userType' => $userType,
				'orgCode' => $orgCode,
				'loginName' => $loginName,
				'siteIds' => $siteIds
			));
			$cookieData = array(
				'userId' => $userId,
				'startTimeStamp' => $startTimeStamp,
				'userData' => $userData,
				'liv' => md5($userData.self::$_md5salt.$userId.self::$_md5salt2.$startTimeStamp)
			);
			$this->_updateCookie($cookieData);
			$this->_isLogin = true;
			return $cookieData;
		} else {
			return false;
		}
	}

	public function logout()
	{
		setcookie('userId', '', 1, '/');
		setcookie('startTimeStamp', '', 1, '/');
		setcookie('userData', '', 1, '/');
		setcookie('liv', '', 1, '/');
		$this->_isLogin = false;
	}

	public function isLogin()
	{
		if(isset($_COOKIE['userId']) && $_COOKIE['userId'] != '') {
			$livToken = md5($_COOKIE['userData'].self::$_md5salt.$_COOKIE['userId'].self::$_md5salt2.$_COOKIE['startTimeStamp']);
			if($livToken == $_COOKIE['liv']) {
				$isLogin = true;
			} else {
				$isLogin = false;
			}
		} else {
			$isLogin = false;
		}
		return $isLogin;
	}

	public function getUserId()
	{
		return $_COOKIE['userId'];
	}

	public function getUserData()
	{
		return $_COOKIE['userData'];
	}

	public function _updateCookie($cookies)
	{
		foreach($cookies as $k => $v) {
			setcookie($k, $v, time()+60*60*24*7, '/');
		}
	}
}
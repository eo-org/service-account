<?php
namespace Admin;

use Zend\Mvc\MvcEvent;
use Core\Brick\Register;

class RegisterConfig
{
	public function configRegister(Register $register)
	{
		$register->registerBrick(array(
				'Admin\ActionTitle',
				'Admin\ActionMenu',
				'Admin\AdminToolbar'
		));
	}
}
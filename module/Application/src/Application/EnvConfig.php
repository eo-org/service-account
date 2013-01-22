<?php
namespace Application;

class EnvConfig
{
	public $apiKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	
	public $libUrl;
	public $extUrl;
	
	public function __construct()
	{
		$this->libUrl = 'http://lib.eo.test/cms/v3';
		$this->extUrl = 'http://lib.eo.test/ext';
	}
}
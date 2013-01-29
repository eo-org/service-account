<?php
namespace Application;

class EnvConfig
{
	public $apiKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	
	public $libUrl;
	public $extUrl;
	
	public function __construct($config)
	{
		$this->libUrl = 'http://'.$config['fileServer'].'/cms/'.$config['libVersion'];
		$this->extUrl = 'http://'.$config['fileServer'].'/ext';
	}
}
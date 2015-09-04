<?php
/**
 * EBTConfig
 *
 * Global class for implementing code used by others classes.
 *
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-03
 */
require_once('ebtconfig.php');

class EBTGlobal
{
	var $config;
	
	function __construct()
	{
		$this->config = new EBTConfig();
	}
}

<?php
/**
 * EBTWriter
 *
 * Creates files to interact with SMS Server Tool v3.
 *
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-03
 */
require_once('ebtglobal.php');

class EBTWriter extends EBTGlobal
{
	private $_headers;
	private $_message;
	private $_content;

	function __construct($headers, $msg = "")
	{
		parent::__construct();
		$this->_headers = $headers;
		$this->_message = $msg;
		$this->_set_content();
		return $this;
	}

	protected function _set_content()
	{
		$this->_content = "";
		foreach ($this->_headers as $key => $value)
		{
			$this->_content .= $key . ": " . $value . "\n";
		}
		$this->_content .= "\n" . $this->_message;
	}

	function get_content()
	{
		return $this->_content;
	}

	function write()
	{
		$sRndCode = substr(str_shuffle(MD5(microtime())), 0, 6);
		$fname = date('YmdHis') . '.' . $sRndCode;
		$hFile = fopen($this->config->outgoing . '/' . $fname . $this->config->sms_ext, 'w');
		if ($hFile)
		{
			fwrite($hFile, $this->_content);
		}
		return $this;
	}
}

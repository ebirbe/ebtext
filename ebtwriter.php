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
	var $sender;
	var $message;

	function __construct($sndr, $msg = "")
	{
		parent::__construct();
		$this->sender = $sndr;
		$this->message = $msg;
	}

	function get_content()
	{
		$data = "To: " . $this->sender . "\n";
		$data .= "\n";
		$data .= $this->message;
		return $data;
	}

	function write()
	{
		$fname = date('YmdHis');
		
		$hFile = fopen($this->config->outgoing . '/' . $fname . $this->config->sms_ext, 'w');
		
		if (!$hFile)
		{
			return FALSE;
		}
		return fwrite($hFile, $this->get_content());
	}
}

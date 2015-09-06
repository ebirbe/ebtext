<?php
/**
 * EBTReader
 *
 * Reads files in SMS Server Tool v3 format.
 *
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-04
 */
require_once('ebtglobal.php');

class EBTReader extends EBTGlobal
{
	var $filename;

	function __construct($file)
	{
		parent::__construct();
		$this->filename = $file;
	}

	function read()
	{
		if (is_file($this->filename) and is_readable($this->filename))
		{
			$hFile = fopen($this->filename, 'r');
			$iSize = filesize($this->filename);
			if (!$hFile)
			{
				return FALSE;
			}
			if ($sContent = fread($hFile, $iSize))
			{
				return $sContent;
			}
		}
		return FALSE;
	}
}

// ---------------------------------------------------------------------
// MAIN
// ---------------------------------------------------------------------
if (!debug_backtrace())
{
	$obj = new EBTReader('/var/spool/sms/incoming/2015-09-03.GSM1.0CMrMT');
	echo $obj->read();
}

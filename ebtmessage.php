<?php
/**
 * EBTMessage
 *
 * Message Class for EBText system.
 *
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-04
 */
require_once('ebtglobal.php');
require_once('ebtreader.php');
require_once('ebtwriter.php');

define('EBTMSG_FILE', 0);
define('EBTMSG_CONTENT', 1);
define('EBTMSG_NONE', 2);

class EBTMessage extends EBTGlobal
{
	private $_raw;
	private $_headers_raw;
	private $_headers;
	private $_message;
	private $_filename;

	function __construct($message = NULL, $type = EBTMSG_NONE)
	{
		parent::__construct();

		switch ($type)
		{
			case EBTMSG_FILE:
				$this->read($message);
				break;
			case EBTMSG_CONTENT:
				$this->_raw = $message;
				break;
			default:
				break;
		}
		return $this;
	}

	protected function _get_headers_raw()
	{
		if (!$this->_headers_raw)
		{
			$this->_headers_raw = split("\n\n", $this->_raw)[0];
		}
		return $this->_headers_raw;
	}

	protected function _get_headers()
	{
		if (!$this->_headers)
		{
			$vHeadersRaw = split("\n", $this->_get_headers_raw());
			$vHeaders = array();
			foreach ($vHeadersRaw as $sHeader)
			{
				$vData = split(": ", $sHeader);
				$vNew = array($vData[0] => $vData[1]);
				$vHeaders = array_merge($vHeaders, $vNew);
			}
			$this->_headers = $vHeaders;
		}
		return $this->_headers;
	}

	function get_header($name)
	{
		if (array_key_exists($name, $this->_get_headers()))
		{
			return $this->_headers[$name];
		}
	}

	function set_header($name, $value = NULL)
	{
		if ($value)
		{
			$this->_headers[$name] = $value;
		}
		return $this;
	}

	function get_message()
	{
		if (!$this->_message)
		{
			$vContent = split("\n\n", $this->_raw);
			array_shift($vContent);

			$sMsg = '';
			foreach($vContent as $sLine)
			{
				$sMsg .= $sLine . "\n\n";
			}
			// Cleans the last unnecessary '\n\n' characters.
			$this->_message = substr_replace($sMsg, "", -2);
		}
		return $this->_message;
	}

	function set_message($msg)
	{
		$this->_message = $msg;
		return $this;
	}

	function set_receiver($number)
	{
		$this->header('To', $number);
		return $this;
	}

	function read($filename)
	{
		$this->_filename = $filename;
		$oReader = new EBTReader($filename);
		$this->_raw = $oReader->read();
		//TODO: Rebuild de message object with the new data loaded.
		return $this;
	}

	function write($filename = NULL)
	{
		$oWriter = new EBTWriter($this->_headers, $this->_message);
		if ($oWriter->write())
		{
			throw new Exception('Unable to write file ' . $filename);
		}
		return $this;
	}
}

// ---------------------------------------------------------------------
// MAIN
// ---------------------------------------------------------------------
if (!debug_backtrace())
{
	/*
	// Creating SMS from a content string
	$oReader = new EBTReader();
	$sContent = $oReader->read();

	$oMessage = new EBTMessage($sContent, EBTMSG_CONTENT);
	echo $oMessage->get_message();
	echo "----";
	echo $oMessage->get_header('From');
	echo $oMessage->get_header('Alphabet');
	echo $oMessage->get_header('Help');
	echo "----";
	$oMessage->write();
	*/

	/*
	// Creating SMS from a File
	$oMessage = new EBTMessage('examples/sms_incoming_file.txt', EBTMSG_FILE);
	echo $oMessage->get_message();
	echo "----";
	echo $oMessage->get_header('From');
	echo $oMessage->get_header('Alphabet');
	echo $oMessage->get_header('Help');
	echo "----";
	$oMessage->write();
	*/

	/*
	$oMessage = new EBTMessage();
	$oMessage->set_header('To', '04128663381');
	$oMessage->set_message("Conchale vale que está pasando Dios mío.");
	$oMessage->write();
	**/
}

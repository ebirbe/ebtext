<?php
require_once('ebtmessage.php');

class EBTMsgReceived extends EBTMessage
{

	function __construct($filename)
	{
		parent::__construct($filename, EBTMSG_FILE);
	}

	function execute()
	{
		// Read the message
		$sClientNumber = $this->get_header('From');
		$sMsgIn = trim($this->get_message());
		$sMsgOut = "";
		
		if (strpos(strtoupper($sMsgIn), 'ATENCION') === FALSE)
		{
			$sMsgOut = "Lo siento, este telefono esta siendo usado, abusado y explotado para mandar SMS automaticos. Un dato innecesario: son las " . date('h:i:s A');
		}
		else
		{
			$sComando = trim(str_ireplace('ATENCION', '', $sMsgIn));
			$sMsgOut = "OK, me dijiste: '" . $sComando . "'.\n\nPero igual no hago nada con eso.";
		}

		// Send the automatic response
		$oResponse = new EBTMessage();
		$oResponse->set_header('To', $sClientNumber);
		$oResponse->set_message($sMsgOut);
		$oResponse->write();
	}
}

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
			$sMsgOut = "No entiendo lo que me dices, deberias buscar un manual o llamar a alguien que te asesore de como usarme, mientras tanto me la echaré de flojo porque no me has mandado a hacer nada... :-P";
		}
		else
		{
			$sComando = trim(str_ireplace('ATENCION', '', $sMsgIn));
			$sMsgOut = "Qué bueno! Algo que entiendo!... Me dijiste: '" . $sComando . "'. Bueno, pero ya... no te emociones mucho xq esto es lo unico que hago por ahora.";
		}

		// Send the automatic response
		$oResponse = new EBTMessage();
		$oResponse->set_header('To', $sClientNumber);
		$oResponse->set_message($sMsgOut);
		$oResponse->write();
	}
}

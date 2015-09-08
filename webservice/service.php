<?php
/**
 * EBTBulk is a script for sending one message to several receivers.
 * 
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-08
 * */

define('EBTPATH', '/opt/ebtext/');

/* ********************************************************************
 * MAIN PROCESS
 * ********************************************************************/
require_once EBTPATH.'ebtmessage.php';

function _send_message($to, $message)
{
	echo "Creating SMS to " . $to . "... ";
	$oMessage = new EBTMessage();
	$oMessage->set_header('To', $to)
		->set_message($message)
		->write();
	echo "Created!\n";
	unset($oMessage);
}

function run(array $receivers, $message)
{
	// Clean duplicated phone numbers
	$receivers = array_unique($receivers);

	// Send multiple messages
	foreach ($receivers as $sRcv)
	{
		_send_message($sRcv, $message);
	}
}

// Process the JSON
$sJson = file_get_contents('php://input');
$oParams = json_decode($sJson);

if(!$oParams)
{
	exit(1);
}

$aReceivers = $oParams->to;
$sMessage = $oParams->msg;

// Start the applicantion
run($aReceivers, $sMessage);

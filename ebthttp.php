<?php
/**
 * EBTBulk is a script for sending one message to several receivers.
 * 
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-06
 * */

/* *********************************************************************
 * VALIDATIONS... neccessary to run well
 * ********************************************************************/


/* ********************************************************************
 * MAIN PROCESS
 * ********************************************************************/
require_once('ebtmessage.php');

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



///json
$json = file_get_contents('php://input');
$obj = json_decode($json);
///json


// Extract the receivers


$aReceivers = array($obj->to);
// Get the message
$sMessage = $obj->msg;

// Start the applicantion
run($aReceivers, $sMessage);

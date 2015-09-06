<?php
/**
 * EBTConfig
 *
 * Config class for EBText system.
 *
 * @author	Erick Birbe <erickcion@gmail.com>
 * @date	2015-09-03
 */

class EBTConfig
{
	var $outgoing = "/var/spool/sms/outgoing";
	var $incoming = "/var/spool/sms/incoming";
	var $checked = "/var/spool/sms/checked";
	var $sent = "/var/spool/sms/sent";
	var $failed = "/var/spool/sms/failed";
	var $sms_ext = ".txt";
}

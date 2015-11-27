<?php
require_once('ebtmessage.php');
require_once('eventhandler/ebtmsgreceived.php');

function showFiles($path){
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
			echo $path.$current."\n";
			$file = $path.$current;
			$oMgsRcvd = new EBTMsgReceived($file);
			$oMgsRcvd->execute();
        }
    }
}

showFiles("/home/erick/Desktop/mensajes_fallidos/");

?>

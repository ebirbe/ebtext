<?php

$ebt_service = 'http://localhost/~erick/ebtservice/service.php';

$param = array(
	"to" => array(
		'04128663381',
	),
	"msg" => "Hola mundo.\n\n" . date('Y-m-d H:i:s A'),
);

$data_string = json_encode($param);

$ch = curl_init($ebt_service);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);

echo $result;
?>

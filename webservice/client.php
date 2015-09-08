<?php

$ebt_service = 'http://localhost/~erick/ebtservice/ebthttp.php';

$param = array(
	"to" => '04128663381',
	"msg" => "Hola mundo",
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

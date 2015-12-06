<?php
require_once('ebtmessage.php');

class EBTMsgReceived extends EBTMessage
{
	public $validation_server = 'http://nuestravictoria.org.ve/sms/validar.php';
	//public $validation_server = 'http://localhost/~erick/militantes/sms/validar.php';

	function __construct($filename)
	{
		parent::__construct($filename, EBTMSG_FILE);
	}

	function execute()
	{
		// Read the message
		$sClientNumber = $this->get_header('From');
		$sDateSent = $this->get_header('Sent');
		$sMsgIn = trim($this->get_message());
		$sMsgOut = "";
		$sOldRes = "";
		$aResp = array(
			'CI_NOVAL' => "",
			'CI_NOREG' => "",
			'CI_ERROR' => "",
		);

		if (strlen($sClientNumber) < 6)
		{
			echo 'ERROR: Numero de telefono muy corto (' . $sClientNumber . ')';
			return;
		}

		echo "--- PHONE: " . $sClientNumber . "---\n";
		echo $sMsgIn . "\n";
		echo "--- END SMS ---\n";

		if (strtoupper($sMsgIn) == 'TOTAL')
		{
			$sMsgIn = '0000001';
		}

		$sMsgIn = preg_replace('/[^0-9]/', ' ', $sMsgIn);
		$aCedulas = split(' ', $sMsgIn);
		foreach ($aCedulas as $sCedula)
		{
			// Sanitize
			$sCedula =  trim($sCedula);

			// Si el mensaje esta en blanco es solo texto no hace nada
			if (empty($sCedula) or !is_numeric($sCedula))
			{
				continue;
			}

			// Si la cedula esta fuera de rango
			if(strlen($sCedula) < 5 or strlen($sCedula) > 8)
			{
				$aResp['CI_NOVAL'] .= $sCedula . "\n";
			}
			else
			{
				$param = array(
					"cedula" => $sCedula,
					"enviado" => $sDateSent,
					"telf_auto" => $sClientNumber,
				);

				$data_string = json_encode($param);
				$ch = curl_init($this->validation_server);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($data_string)
					)
				);
				// PROXY
				curl_setopt($ch, CURLOPT_PROXY, "172.16.2.8");
				curl_setopt($ch, CURLOPT_PROXYPORT, "3128");
				curl_setopt($ch, CURLOPT_PROXYTYPE, "HTTP");

				$result = curl_exec($ch);
				$jData = json_decode($result);
				var_dump($result);
				// var_dump($jData);

				if ($jData !== NULL)
				{
					if ($jData->existe == 1)
					{
						// $sMsgOut = "Se ha registrado " . $jData->cedula . " con exito.";
						continue;
					}
					else
					{
						if($jData->existe == 'MSJ')
						{
							$aResp['MSJ'] = $jData->mensaje;
						}
						// $aResp['CI_NOREG'] .= $sCedula . "\n";
						continue;
					}
				}
				else
				{
					if($sOldRes == $result)
					{
						$result = NULL;
					}
					$aResp['CI_ERROR'] .= $result . "\n" . $sCedula;
					$sOldRes = $result;
				}
			}
		}

		foreach($aResp as $key => $value)
		{
			$sMsgOut = "";
			if ($value  != "")
			{
				switch ($key)
				{
					case 'CI_NOVAL':
						$sMsgOut = "(Utilice solo numeros y espacios)\n";
						$sMsgOut .= "NO ES UNA CEDULA:\n";
						break;
					case 'CI_NOREG':
						$sMsgOut = "NO ESTA REGISTRADA:\n";
						break;
					case 'CI_ERROR':
						$sMsgOut = "ERROR DE TRANSMISION:\n";
						break;
					case 'MSJ':
						$sMsgOut = "MENSAJE:\n";
						break;
					default:
						break;
				}
				$sMsgOut .= $value;

				// Send the automatic response
				$oResponse = new EBTMessage();
				$oResponse->set_header('To', $sClientNumber);
				$oResponse->set_message($sMsgOut);
				$oResponse->write();
			}
		}

	}
}

<?php

$filePath = './efi-api/certificates/CertProd.pem';  // EM PRODUÇÃO

// $filePath = './certificates/CertProd.pem';  // PARA TESTES LOCAL

try {

    $config = [
        "certificado" => "$filePath",
        "client_id" => "Client_Id_XXXXXX",
        "client_secret" => "Client_Secret_XXXXXX"
    ];
    $autorizacao =  base64_encode($config["client_id"] . ":" . $config["client_secret"]);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pix.api.efipay.com.br/oauth/token", //https://pix.api.efipay.com.br - https://pix-h.api.efipay.com.br/oauth/token Rotas base, homologação ou produção
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
		CURLOPT_SSLCERT => $config["certificado"], // Caminho do certificado
		CURLOPT_SSLCERTPASSWD => "",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Basic $autorizacao"
        ),
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        throw new Exception(curl_error($curl));
    }

    curl_close($curl);
    
	$data = json_decode($response, true); // Decodifique a resposta JSON para um array associativo

	if (isset($data['access_token'])) {
		$access_token = $data['access_token']; // Armazene o access_token em uma variável
	} else {
		echo "Erro ao obter o access_token.";
	}
	
	
} catch (Exception $e) {
    echo "Ocorreu um erro: " . $e->getMessage();
}

?>
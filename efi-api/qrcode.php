<?php
// Inclua suas credenciais
require_once 'credenciais.php';

$curl = curl_init();

// Use aspas duplas para interpolar a variável $access_token
$headers = array(
    "authorization: Bearer $access_token",
    "Content-Type: application/json"
);

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://pix.api.efipay.com.br/v2/loc/16/qrcode',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_SSLCERT => $config["certificado"], // Caminho do seu certificado
    CURLOPT_SSLCERTPASSWD => "",
    CURLOPT_HTTPHEADER => $headers, // Use o array de cabeçalhos
));

$response = curl_exec($curl);

curl_close($curl);

if ($response) {
    $data = json_decode($response, true);

    if (isset($data['qrcode'])) {
        $qrcode = $data['qrcode'];
        $imagemQrcode = $data['imagemQrcode'];
        echo "QR Code: " . $qrcode;
    } else {
        echo "A chave 'qrcode' não foi encontrada no JSON.";
    }
} else {
    echo "Não foi possível obter uma resposta do servidor.";
}
?>
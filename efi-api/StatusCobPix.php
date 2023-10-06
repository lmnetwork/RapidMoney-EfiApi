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
  CURLOPT_URL => 'https://pix.api.efipay.com.br/v2/cob/59a27a0ba7f645dd8c4ed4781a134d7f',
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

// Decodificar a resposta JSON
$data = json_decode($response, true); // O segundo parâmetro true retorna um array associativo

if ($data) {
    // Acesso às informações individualmente

    $criacao = $data['calendario']['criacao'];
    $expiracao = $data['calendario']['expiracao'];
    $txid = $data['txid'];
    $revisao = $data['revisao'];
    $locId = $data['loc']['id'];
    $locLocation = $data['loc']['location'];
    $locTipoCob = $data['loc']['tipoCob'];
    $locCriacao = $data['loc']['criacao'];
    $location = $data['location'];
    $status = $data['status'];
    $devedorCPF = $data['devedor']['cpf'];
    $devedorNome = $data['devedor']['nome'];
    $valorOriginal = $data['valor']['original'];
    $chave = $data['chave'];
    $solicitacaoPagador = $data['solicitacaoPagador'];

    // Agora você pode usar essas variáveis conforme necessário
    echo "Criação: $criacao<br>";
    echo "Expiração: $expiracao<br>";
    echo "Txid: $txid<br>";
    echo "Status: $status<br>";
} else {
    echo "Não foi possível decodificar a resposta JSON.";
}

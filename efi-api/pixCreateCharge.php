<?php
// Inclua suas credenciais
require_once 'credenciais.php';

// Dados da requisição de pagamento
$body = [
    "calendario" => [
        "expiracao" => 3600 // Tempo de vida da cobrança em segundos
    ],
    "devedor" => [
        "cpf" => "89015118515",
        "nome" => "Irena Pereira dos Santos"
    ],
    "valor" => [
        "original" => "3.00"
    ],
    "chave" => "pix@rapidmoney.app", // Sua chave Pix registrada na conta Efí
    "solicitacaoPagador" => "Assinatura 89015118515"
];

// Codificar o corpo da requisição em JSON
$requestBody = json_encode($body);

// Iniciar a solicitação cURL
$curl = curl_init();

// Configurar as opções da solicitação cURL
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://pix.api.efipay.com.br/v2/cob", // Substitua pelo caminho real da API
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $requestBody,
    CURLOPT_SSLCERT => $config["certificado"], // Caminho do seu certificado
    CURLOPT_SSLCERTPASSWD => "",
    CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $access_token",
    "Content-Type: application/json"
  ),
));

// Executar a solicitação cURL
$response = curl_exec($curl);

// Verificar se houve erros na solicitação
if (curl_errno($curl)) {
    echo 'Erro na requisição cURL: ' . curl_error($curl);
}

// Decodificar a resposta JSON
$data = json_decode($response, true);

// Verificar se a decodificação foi bem-sucedida
if ($data === null) {
    echo 'Erro ao decodificar JSON.';
} else {
    // Criar variáveis para cada valor
    $calendarioCriacao = $data['calendario']['criacao'];
    $calendarioExpiracao = $data['calendario']['expiracao'];
    $txid = $data['txid'];
    $revisao = $data['revisao'];
    $locId = $data['loc']['id'];
    $locLocation = $data['loc']['location'];
    $locTipoCob = $data['loc']['tipoCob'];
    $locCriacao = $data['loc']['criacao'];
    $location = $data['location'];
    $status = $data['status'];
    $devedorCpf = $data['devedor']['cpf'];
    $devedorNome = $data['devedor']['nome'];
    $valorOriginal = $data['valor']['original'];
    $chave = $data['chave'];
    $solicitacaoPagador = $data['solicitacaoPagador'];

    // Exibir os valores das variáveis
    echo "calendarioCriacao: $calendarioCriacao<br>";
    echo "calendarioExpiracao: $calendarioExpiracao<br>";
    echo "txid: $txid<br>";
    echo "revisao: $revisao<br>";
    echo "locId: $locId<br>";
    echo "locLocation: $locLocation<br>";
    echo "locTipoCob: $locTipoCob<br>";
    echo "locCriacao: $locCriacao<br>";
    echo "location: $location<br>";
    echo "status: $status<br>";
    echo "devedorCpf: $devedorCpf<br>";
    echo "devedorNome: $devedorNome<br>";
    echo "valorOriginal: $valorOriginal<br>";
    echo "chave: $chave<br>";
    echo "solicitacaoPagador: $solicitacaoPagador<br>";
}

// Fechar a conexão cURL
curl_close($curl);
?>
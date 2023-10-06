<?php

// Verifica se a solicitação é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar o conteúdo do webhook
    $webhookData = file_get_contents("php://input");

    if ($webhookData === false) {
        // Lidar com erros de requisição aqui, se necessário
    } else {
        // Processar os dados do webhook (por exemplo, converter de JSON)
        $jsonData = json_decode($webhookData, true);

        if ($jsonData === null) {
            // Lidar com erros de decodificação JSON aqui, se necessário
        } else {
            // Os dados do webhook foram decodificados com sucesso
            // Agora você pode acessar os campos do webhook como no exemplo da documentação

            $endToEndId = $jsonData["pix"][0]["endToEndId"];
            $txid = $jsonData["pix"][0]["txid"];
            $chave = $jsonData["pix"][0]["chave"];
            $valor = $jsonData["pix"][0]["valor"];
            $horario = $jsonData["pix"][0]["horario"];
            $infoPagador = $jsonData["pix"][0]["infoPagador"];

            // Faça algo com os dados recebidos, como registrar no banco de dados ou realizar ações específicas

            // Você pode responder com um status 200 OK para indicar que recebeu o callback com sucesso
            http_response_code(200);
            echo "Callback recebido com sucesso!";
        }
    }
} else {
    // Se a solicitação não for POST, retorne um erro ou uma resposta adequada
    http_response_code(400); // Bad Request
    echo "Solicitação inválida";
}

?>

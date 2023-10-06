<?php
include("CRUDS.php");

// Inclua suas credenciais
require_once "./efi-api/credenciais.php";

$NewSign = $_GET['New'] ?? null; // Obter o valor do parâmetro GET 'NewSign'
$opensign = $_GET['NSU'] ?? null; // Obter o valor do parâmetro GET 'opensign'

$cookie_NSU = isset($_SESSION['NSU']) ? $_SESSION['NSU'] : '';
$cookie_numero = isset($_SESSION['numero']) ? $_SESSION['numero'] : '';
$cookie_calendarioCriacao = isset($_SESSION['calendarioCriacao']) ? date("d/m/Y - H:i", strtotime($_SESSION['calendarioCriacao'])) : '';
$cookie_IdTrans = isset($_SESSION['IdTrans']) ? $_SESSION['IdTrans'] : '';
$cookie_txid = isset($_SESSION['txid']) ? $_SESSION['txid'] : '';	?>

<div class="fixed-top">
	<div class="appbar-area sticky-black">
		<div class="container">
			<div class="appbar-container">
				<div class="appbar-item appbar-actions">
					<div class="appbar-action-item">
						<a href="#" class="back-page"><i class="flaticon-left-arrow"></i></a>
					</div>
					<div class="appbar-action-item">
						<a href="#" class="appbar-action-bar" data-bs-toggle="modal" data-bs-target="#sidebarDrawer"><i class="flaticon-menu"></i></a>
					</div>
				</div>
					<div class="appbar-item appbar-page-title">
						<h3>Minhas Assinaturas</h3>
					</div>
				<div class="appbar-item appbar-options">
					<div class="appbar-option-item appbar-option-notification">
						<a href="notifications"><i class="flaticon-bell"></i></a>
						<span class="option-badge"><?= $total_noticies ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
        <!-- Appbar -->
<div class="body-content">
            <div class="container">
				
				<?php if($assinaturas['total'] == 30){ ?>
				
				<div class="error-page-content">
                    <img src="assets/images/404.png" alt="404">
                    <h2>ATENÇÃO! Limite atingido.</h2>
                    <p>O nosso sistema possui uma limitação de até 30 assinaturas ativas por conta. Para adquirir novas assinaturas, é necessário que as assinaturas ativas sejam concluídas. A cada assinatura concluída, você terá a possibilidade de adquirir uma nova.</p>
                    <a href="home" class="btn main-btn">Voltar ao início</a>
                </div>
				
				<?php } ?>
				<?php
				if ($opensign !== null AND $assinaturas['total'] < 30): ?>
                <!-- Payment-list -->
				<div class="page-header pb-15">
                    <div class="page-header-title page-header-item">
                        <h3>Informações da assinatura</h3>
                    </div>
                </div>
				<?php $conexao = conexao::getInstance();
				$sql = "SELECT * FROM assinaturas WHERE NSU = ".$opensign;
				$stm = $conexao->prepare($sql); $stm->execute();
				$signs = $stm->fetchAll(PDO::FETCH_OBJ);
				foreach($signs as $sign): ?>
                <div class="pb-30">
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Status:</b> <?php if($sign->Status == 1) {echo"Ativa";} if($sign->Status == 0) {echo"Inativa";} ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Data da Assinatura:</b> <?=date('d/m/Y', strtotime($sign->DataAssinatura))?></div>
                    </div>
                    <div class="payment-list-details">
                       <div class="payment-list-item payment-list-title"><b>Id Transação:</b> SSID<?=$sign->id?>-<?=$sign->IdTransacao?>-UID<?=$sign->Uid?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>NSU:</b> <?=$sign->NSU?></div>
                    </div>
					<div class="payment-list-details">
                        <div class="payment-list-item payment-list-info">
						<p style="text-align:center; font-size: 14px">Autenticação<br><?=md5($sign->DataAssinatura)?></p>
						<p style="text-align:center; font-size: 10px">Assinatura adquirida em: <?=date('d/m/Y - H:i:s', strtotime($sign->DataAssinatura))?></p>
						<p style="text-align:center; font-size: 12px"><i style="font-size:12px" class="material-icons-outlined">info</i>
						Caso você tenha alguma dúvida sobre essa operação, acesse o nosso chat e informe o número da Transação.</p>
						</div>
                    </div>
                </div>
				<?php endforeach; ?>
				
				<?php
				elseif ($NewSign == "TipoPagamento" AND $assinaturas['total'] < 30): ?>
				
				<div class="monthly-bill-section pb-15">
                    <div class="section-header">
                        <h2>Forma de pagamento</h2>
                    </div>
                    <div class="row gx-3">
						<div class="col-6 pb-15">
                            <div onclick="location.href='assinaturas&New=PayPIX'" class="monthly-bill-card monthly-bill-card-green">
                                <div class="monthly-bill-thumb">
                                    <img src="assets/images/logo_pix.png" alt="logo">
                                </div>
                                <div class="monthly-bill-body">
                                    <h3><a>PIX</a></h3>
                                    <p>Pague à vista sem juros</p>
                                </div>
                                <div class="monthly-bill-footer monthly-bill-action">
                                    <a class="btn main-btn">Selecionar</a>
                                    <p class="monthly-bill-price">R$ 120,00</p>
                                </div>
                            </div>
                        </div>
						<?php
						$sql = "SELECT * FROM users WHERE id = ".$_SESSION["DocID"];
						$stm = $conexao->prepare($sql);
						$stm->execute();
						$user = $stm->fetch(PDO::FETCH_OBJ);
						if($user->SaldoTotal >= "120"){ ?>
						<div class="col-6 pb-15">
                            <div onclick="location.href='assinaturas&New=PaySALDO'" class="monthly-bill-card monthly-bill-card-green">
                                <div class="monthly-bill-thumb">
                                    <img src="assets/images/saldo_conta.png" alt="logo">
                                </div>
                                <div class="monthly-bill-body">
                                    <h3>Saldo em conta</h3>
                                    <p>Pague c/ saldo sem juros</p>
                                </div>
                                <div class="monthly-bill-footer monthly-bill-action">
                                    <a class="btn main-btn">Selecionar</a>
                                    <p class="monthly-bill-price">R$ 120,00</p>
                                </div>
                            </div>
                        </div>
						<?php } ?>
                        <div class="col-6 pb-15">
                            <div class="monthly-bill-card monthly-bill-card-green">
                                <div class="monthly-bill-thumb">
                                    <img src="assets/images/logo_credit_card.png" alt="logo">
                                </div>
                                <div class="monthly-bill-body">
                                    <h3>Cartão de crédito</h3>
                                    <p>Parcele até 12x c/ juros</p>
                                </div>
                                <div class="monthly-bill-footer monthly-bill-action">
                                    <a id="PagamentoCartao" class="btn main-btn">Selecionar</a>
                                    <p class="monthly-bill-price">R$ 120,00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				<?php
				elseif ($NewSign == "PayPIX" AND $assinaturas['total'] < 30):

				// Sua conexão com o banco de dados
					$conexao = conexao::getInstance();
				
				// Função genérica para gerar um número aleatório dentro de um intervalo
					function gerarNumeroAleatorio($min, $max) {
						return mt_rand($min, $max);
					}

					// Função genérica para verificar a existência de um valor no banco de dados
					function verificarExistencia($valor, $campo, $conexao) {
						$sql = "SELECT * FROM assinaturas WHERE $campo = :Valor";
						$stm = $conexao->prepare($sql);
						$stm->bindValue(':Valor', $valor);
						$stm->execute();
						$resultado = $stm->fetchAll(PDO::FETCH_OBJ);
						return count($resultado) > 0;
					}

					// Geração de NSU único
					$NSU = gerarNumeroAleatorio(10000000, 99999999);
					while (verificarExistencia($NSU, 'NSU', $conexao)) {
						$NSU = gerarNumeroAleatorio(10000000, 99999999);
					}

					// Geração de número da sorte único
					$numero = gerarNumeroAleatorio(10000, 99999);
					while (verificarExistencia($numero, 'NumeroSorte', $conexao)) {
						$numero = gerarNumeroAleatorio(10000, 99999);
					}

					// Geração de IdTransação único
					$IdTrans = gerarNumeroAleatorio(1000000, 9999999);
					while (verificarExistencia($IdTrans, 'IdTransacao', $conexao)) {
						$IdTrans = gerarNumeroAleatorio(1000000, 9999999);
					}

					// Armazenamento em sessão
					$_SESSION['NSU'] = $NSU;
					$_SESSION['numero'] = $numero;
					$_SESSION['IdTrans'] = $IdTrans;
				
				foreach ($users as $user):
					$CPF = $user->DocCPF;
					$NOME = $user->NomeCompleto;
				endforeach;

				// Dados da requisição de pagamento
				$body = [
					"calendario" => [
						"expiracao" => 3600 // 3600 Tempo de vida da cobrança em segundos
					],
					"devedor" => [
						"cpf" => "$CPF",
						"nome" => "$NOME"
					],
					"valor" => [
						"original" => "120.00"
					],
					"chave" => "pix@rapidmoney.app", // Sua chave Pix registrada na conta Efí
					"solicitacaoPagador" => "Assinatura $NSU"
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
					$txid = $data['txid'];
					$calendarioCriacao = $data['calendario']['criacao'];
					$locId = $data['loc']['id'];
					
					// Armazenar numero da sorte na sessão
					$_SESSION['txid'] = $txid;
					
					// Armazenar IdTransação na sessão
					$_SESSION['calendarioCriacao'] = $calendarioCriacao;
				}

				// Fechar a conexão cURL
				curl_close($curl);
				
				//FIM DA API DE GERAÇÃO DA COBRANÇA
				
				?>

				<!-- aqui entra o conteúdo para compra da assinatura -->
				<!-- Page-header -->
                <div class="page-header">
                    <div class="page-header-title page-header-item">
                        <h3>Informações da Assinatura</h3>
                        <p>Segue informações abaixo sobre a assinatura</p>
                    </div>
                </div>
                <!-- Page-header -->
                
                <!-- Payment-list -->
                <div class="payment-list pb-30">
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Valor da assinatura:</b> R$ 120,00</div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Data da compra:</b> <?= date("d/m/Y - H:i", strtotime($calendarioCriacao)); ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>NSU:</b> <?= $NSU ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Id Transação:</b> <?= $IdTrans ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Número da sorte:</b> <?= $numero ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Autenticação:</b> <?= $txid ?></div>
                    </div>
                </div>
                <!-- Payment-list -->

                <!-- Button-group -->
                <div class="button-group pb-15">
                    <div class="row gx-2">
                        <div class="col-6">
                            <a href="#" class="btn main-btn main-btn-black back-page">Cancelar</a>
                        </div>
                        <div class="col-6">
                            <a href="assinaturas&New=EndPayPix&IdEfi=<?=base64_encode($locId)?>&TxId=<?=$txid?>" class="btn main-btn">Prosseguir pagamento</a>
                        </div>
                    </div>
                </div>
                <!-- Button-group -->
				
				<?php
				elseif ($NewSign == "PaySALDO" AND $assinaturas['total'] < 30):

				// Sua conexão com o banco de dados
					$conexao = conexao::getInstance();
					
					$sql = "SELECT * FROM users WHERE id = ".$_SESSION["DocID"];
					$stm = $conexao->prepare($sql);
					$stm->execute();
					$user = $stm->fetch(PDO::FETCH_OBJ);
				
				
				// Função genérica para gerar um número aleatório dentro de um intervalo
					function gerarNumeroAleatorio($min, $max) {
						return mt_rand($min, $max);
					}

					// Função genérica para verificar a existência de um valor no banco de dados
					function verificarExistencia($valor, $campo, $conexao) {
						$sql = "SELECT * FROM assinaturas WHERE $campo = :Valor";
						$stm = $conexao->prepare($sql);
						$stm->bindValue(':Valor', $valor);
						$stm->execute();
						$resultado = $stm->fetchAll(PDO::FETCH_OBJ);
						return count($resultado) > 0;
					}

					// Geração de NSU único
					$NSU = gerarNumeroAleatorio(10000000, 99999999);
					while (verificarExistencia($NSU, 'NSU', $conexao)) {
						$NSU = gerarNumeroAleatorio(10000000, 99999999);
					}

					// Geração de número da sorte único
					$numero = gerarNumeroAleatorio(10000, 99999);
					while (verificarExistencia($numero, 'NumeroSorte', $conexao)) {
						$numero = gerarNumeroAleatorio(10000, 99999);
					}

					// Geração de IdTransação único
					$IdTrans = gerarNumeroAleatorio(1000000, 9999999);
					while (verificarExistencia($IdTrans, 'IdTransacao', $conexao)) {
						$IdTrans = gerarNumeroAleatorio(1000000, 9999999);
					}

					// Armazenamento em sessão
					$_SESSION['NSU'] = $NSU;
					$_SESSION['numero'] = $numero;
					$_SESSION['IdTrans'] = $IdTrans;
					
					// Armazenar data da compra na sessão
					$_SESSION['calendarioCriacao'] = date("d/m/Y - H:i");
					$cookie_calendarioCriacao = isset($_SESSION['calendarioCriacao']) ? $_SESSION['calendarioCriacao'] : ''; ?>
				
				<?php if($user->SaldoTotal >= "120"){ ?>
				<!-- Page-header -->
                <div class="page-header">
                    <div class="page-header-title page-header-item">
                        <h3>Informações da Assinatura</h3>
                        <p>Segue informações abaixo sobre a assinatura</p>
                    </div>
                </div>
                <!-- Page-header -->
                
                <!-- Payment-list -->
                <div class="payment-list pb-30">
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Valor da assinatura:</b> R$ 120,00</div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Data da compra:</b> <?= $cookie_calendarioCriacao ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>NSU:</b> <?= $NSU ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Id Transação:</b> <?= $IdTrans ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Número da sorte:</b> <?= $numero ?></div>
                    </div>
                    <div class="payment-list-details">
                        <div class="payment-list-item payment-list-title"><b>Autenticação:</b> <?= md5($IdTrans) ?></div>
                    </div>
                </div>
                <!-- Payment-list -->

                <!-- Button-group -->
                <div class="button-group pb-15">
                    <div class="row gx-2">
                        <div class="col-6">
                            <a href="home" class="btn main-btn main-btn-black back-page">Cancelar</a>
                        </div>
                        <div class="col-6">
                            <a href="assinaturas&New=EndPaySaldo&IdEfi=<?=base64_encode($NSU)?>&TxId=<?=md5($IdTrans)?>" class="btn main-btn">Prosseguir pagamento</a>
                        </div>
                    </div>
                </div>
                <?php }else{?>
				<section class="error-page-section pb-15">
                    <div class="container">
                        <div class="error-page-content">
                            <img src="assets/images/404_saldo.png" alt="404">
                            <h2>Desculpe, seu saldo não é suficiente!</h2>
							<p>Infelizmente, no momento, você não tem saldo o suficiente na conta RapidMoney para adquirir uma nova assinatura. </p>
                            <a href="#" class="btn main-btn main-btn-green back-page">Voltar ao saque</a>
                        </div>
                    </div>
                </section>
				<?php }?>
				
				<?php
				elseif ($NewSign == "EndPayPix" AND $assinaturas['total'] < 30):
				$TxId = $_GET['TxId'] ?? null; // Obter o id do banco if e gerar o meio de pagamento PIX
				$IdEfi = $_GET['IdEfi'] ?? null; // Obter o id do banco if e gerar o meio de pagamento PIX
				$DecIdEfi = base64_decode($IdEfi);
				
				$curl = curl_init();

				// Use aspas duplas para interpolar a variável $access_token
				$headers = array(
					"authorization: Bearer $access_token",
					"Content-Type: application/json"
				);

				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://pix.api.efipay.com.br/v2/loc/$DecIdEfi/qrcode",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
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
					} else {
						echo "A chave 'qrcode' não foi encontrada no JSON.";
					}
				} else {
					echo "Não foi possível obter uma resposta do servidor.";
				}
				
				//FIM DA GERAÇÃO DE QRCODE PIX
				
				//INICIO DA VERIFICAÇÃO DO STATUS DO PAGAMENTO
				
				$curl = curl_init();

				// Use aspas duplas para interpolar a variável $access_token
				$headers = array(
					"authorization: Bearer $access_token",
					"Content-Type: application/json"
				);
				
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://pix.api.efipay.com.br/v2/cob/$TxId",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_SSLCERT => $config["certificado"], // Caminho do seu certificado
				  CURLOPT_SSLCERTPASSWD => "",
				  CURLOPT_HTTPHEADER => $headers, // Use o array de cabeçalhos
				));
				
				$ResStatus = curl_exec($curl);

				curl_close($curl);

				// Decodificar a resposta JSON
				$DataStatus = json_decode($ResStatus, true); // O segundo parâmetro true retorna um array associativo

				if ($DataStatus) {
					// Acesso às informações individualmente
					$criacao = $DataStatus['calendario']['criacao'];
					$expiracao = $DataStatus['calendario']['expiracao'];
					$status = $DataStatus['status'];
					
					// Converta as strings de data e hora em objetos DateTime
					$expiracaoDateTime = date("d/m/Y - H:i", strtotime($expiracao));
					
					// Data e hora atuais
					$dataHoraAtual = date("d/m/Y - H:i");
				} else {
					echo "Não foi possível decodificar a resposta JSON.";
				} ?>
				
				<div class="about-section pb-15">
                    <div class="text-details">
					
					<?php if($status == "ATIVA"){ ?>
						<script>
							// Função para atualizar a página a cada 15 segundos (15000 milissegundos)
							function atualizarPagina() {
								location.reload();
							}

							// Define o intervalo de atualização
							setInterval(atualizarPagina, 15000); // 15 segundos em milissegundos
						</script>
						
                        <div class='alert alert-warning'><small>Aguardando pagamento para liberação da assinatura.</small></div>
						<h3 style="text-align:center">Cobrança gerada com sucesso!</h3>
                        <p>Escolha uma das opções abaixo para pagar o PIX em seu banco e após efetuar o pagamento, aguarde o sistema identificar e ativar sua assinatura automaticamente.</p>
						<div style="text-align:center" class="form-group mb-15"><label class="form-label">Pix QrCode</label><br><img style="width:200px; height:200px;" src="<?= $imagemQrcode ?>" alt="about"></div>
						<div id="CopyChavePix"  style="text-align:center" class="form-group mb-15">
                            <label class="form-label">Pix copia e cola</label>
                            <textarea class="form-control" rows="4" value="<?=$qrcode?>" id="WhatsCode" readonly><?=$qrcode?></textarea>
                        </div>
						
					<?php } if($status == "CONCLUIDA"){
						
						// Conexão com o banco de dados
						$conexao = conexao::getInstance();

						// Chama a session da id do usuário pego no login para armazenar na tabela
						$cookie_Uid = $_SESSION["DocID"];

						// Verifica se já existe um registro com o mesmo txidAPI
						$sql = "SELECT * FROM assinaturas WHERE txidAPI = :txidAPI";
						$stm = $conexao->prepare($sql);
						$stm->bindParam(':txidAPI', $cookie_txid, PDO::PARAM_STR);
						$stm->execute();
						// Obtém o número de linhas
						$resultado = $stm->rowCount();
						
						// Consulta o registro mais antigo com Status igual a 1
						$sql = "SELECT * FROM assinaturas WHERE Status = 1 ORDER BY id LIMIT 1";
						$stm = $conexao->prepare($sql);
						$stm->execute();
						$assinatura = $stm->fetch(PDO::FETCH_OBJ);
						
						$sql = "SELECT * FROM users WHERE id = ".$_SESSION["DocID"];
						$stm = $conexao->prepare($sql);
						$stm->execute();
						$user = $stm->fetch(PDO::FETCH_OBJ);
						
						if ($resultado == 0) {
								// Se não existe, insere um novo registro
								$sql = 'INSERT INTO assinaturas (Uid, NSU, IdTransacao, NumeroSorte, Status, txidAPI)
										VALUES (:Uid, :NSU, :IdTransacao, :NumeroSorte, 1, :txidAPI)';

								$stm = $conexao->prepare($sql);
								$stm->bindParam(':Uid', $cookie_Uid, PDO::PARAM_STR);
								$stm->bindParam(':NSU', $cookie_NSU, PDO::PARAM_STR);
								$stm->bindParam(':IdTransacao', $cookie_IdTrans, PDO::PARAM_STR);
								$stm->bindParam(':NumeroSorte', $cookie_numero, PDO::PARAM_STR);
								$stm->bindParam(':txidAPI', $cookie_txid, PDO::PARAM_STR);
								$stm->execute();
								
								// Consulta o amigo indicador para bonificar
								foreach ($users as $user):
									$IndicadoPor = $user->AmigoIndicador;
									$BonusPago = $user->BonusPago;

									if ($BonusPago == "Nao") {
										// Pague o bônus de R$ 5,00 para o amigo indicador
										$bonus_valor = 5.00;

										// Atualiza o saldo do amigo indicador
										$sql = "UPDATE users SET SaldoTotal = SaldoTotal + :bonus_valor WHERE id = :idIndicador";
										$stm = $conexao->prepare($sql);
										$stm->bindParam(':bonus_valor', $bonus_valor, PDO::PARAM_STR);
										$stm->bindParam(':idIndicador', $IndicadoPor, PDO::PARAM_INT);
										$stm->execute();

										// Atualiza o bônus para "Sim"
										$sql = "UPDATE users SET BonusPago = 'Sim' WHERE id = :idUsuario";
										$stm = $conexao->prepare($sql);
										$stm->bindParam(':idUsuario', $_SESSION["DocID"], PDO::PARAM_INT);
										$stm->execute();
									}
								endforeach;
								
							// Verifica se ContadorGanhos é igual a 1 ou 2
							if ($assinatura->ContadorGanhos == 0 || $assinatura->ContadorGanhos == 1) {
								
								// Adiciona +1 ao ContadorGanhos
								$contadorGanhosAtualizado = $assinatura->ContadorGanhos + 1;

								// Atualiza o valor de ContadorGanhos no banco de dados
								$sqlUpdate = "UPDATE assinaturas SET ContadorGanhos = :ContadorGanhos WHERE id = :id";
								$stmUpdate = $conexao->prepare($sqlUpdate);
								$stmUpdate->bindParam(':ContadorGanhos', $contadorGanhosAtualizado, PDO::PARAM_INT);
								$stmUpdate->bindParam(':id', $assinatura->id, PDO::PARAM_INT);
								$stmUpdate->execute();
								
								// Valor a ser adicionado ao saldo do usuário
								$ValorAddBlock = 60;

								// Atualiza o valor de saldo do usuário no banco de dados
								$UserSqlUpdate = "UPDATE users SET SaldoBloqueado = SaldoBloqueado + :ValorAddBlock WHERE id = :id";
								$stmUserUpdate = $conexao->prepare($UserSqlUpdate);
								$stmUserUpdate->bindParam(':ValorAddBlock', $ValorAddBlock, PDO::PARAM_INT);
								$stmUserUpdate->bindParam(':id', $assinatura->Uid, PDO::PARAM_STR);
								$stmUserUpdate->execute();
							}
						}

							// Verifica se ContadorGanhos é igual a 3 após a atualização
							if ($assinatura->ContadorGanhos == 2) {
								
								// Valor a ser adicionado ao saldo do usuário
								$valorAdicionado = 180;
								
								// Valor a ser adicionado ao saldo do usuário
								$ValorAddBlock = 0;

								// Atualiza o valor de saldo do usuário no banco de dados
								$UserSqlUpdate = "UPDATE users SET SaldoTotal = SaldoTotal + :valorAdicionado, SaldoBloqueado = :ValorAddBlock WHERE id = :id";
								$stmUserUpdate = $conexao->prepare($UserSqlUpdate);
								$stmUserUpdate->bindParam(':valorAdicionado', $valorAdicionado, PDO::PARAM_INT);
								$stmUserUpdate->bindParam(':ValorAddBlock', $ValorAddBlock, PDO::PARAM_INT);
								$stmUserUpdate->bindParam(':id', $assinatura->Uid, PDO::PARAM_STR);
								$stmUserUpdate->execute();
								
								// Insere os dados na tabela assinaturasconcluidas
								$sqlInsert = "INSERT INTO assinaturasconcluidas (Uid, NSU, IdTransacao, NumeroSorte, DataAssinatura, ContadorGanhos, Status, txidAPI)
												VALUES (:Uid, :NSU, :IdTransacao, :NumeroSorte, :DataAssinatura, :ContadorGanhos, :Status, :txidAPI)";
								$stmInsert = $conexao->prepare($sqlInsert);
								$stmInsert->bindParam(':Uid', $assinatura->Uid, PDO::PARAM_STR);
								$stmInsert->bindParam(':NSU', $assinatura->NSU, PDO::PARAM_STR);
								$stmInsert->bindParam(':IdTransacao', $assinatura->IdTransacao, PDO::PARAM_STR);
								$stmInsert->bindParam(':NumeroSorte', $assinatura->NumeroSorte, PDO::PARAM_STR);
								$stmInsert->bindParam(':DataAssinatura', $assinatura->DataAssinatura, PDO::PARAM_STR);
								$contadorGanhosInsert = 3;
								$stmInsert->bindParam(':ContadorGanhos', $contadorGanhosInsert, PDO::PARAM_INT);
								$stmInsert->bindParam(':Status', $assinatura->Status, PDO::PARAM_INT);
								$stmInsert->bindParam(':txidAPI', $assinatura->txidAPI, PDO::PARAM_STR);
								$stmInsert->execute();

								// Apaga o registro da tabela assinaturas
								$sqlDelete = "DELETE FROM assinaturas WHERE id = :id";
								$stmDelete = $conexao->prepare($sqlDelete);
								$stmDelete->bindParam(':id', $assinatura->id, PDO::PARAM_INT);
								$stmDelete->execute();
								
								//Envia SMS para celular do cliente
								
								$CelularCliente = preg_replace("/[^0-9]/", "", $user->CelularContato);
								
								$msgEncoded = urlencode("RapidMoney: Novo saldo ganho de 180 disponível! Saque ou fazer NOVA ASSINATURA e ganhe mais.");
								$url = "http://api.facilitamovel.com.br/api/simpleSend.ft?user=reembolcash&password=adh132567&destinatario=$CelularCliente&msg=".$msgEncoded;
								$ch = curl_init($url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								curl_exec($ch);
							}
						?>
						
						<div class="alert alert-primary" role="alert">
							<div style="text-align:center"><img src="https://cdn-icons-png.flaticon.com/512/11338/11338402.png" width="150" height="150" alt="" title="" class="img-small"></div>
                            <h4 style="text-align:center" class="alert-heading">Pagamento identificado com sucesso!</h4>
                            <p>Agradecemos pelo seu pagamento, o qual foi prontamente identificado. Informamos que a sua assinatura de rendimento foi ativada com êxito, proporcionando um retorno de R$ 60,00. Você poderá efetuar o saque em 4 dias após a disponibilidade.</p>
                            
							<h4 class="alert-heading">Informações da assinatura</h4>
							<p><b>Status:</b> Ativa</p>
							<hr>
							<p><b>Data e hora:</b> <?=$cookie_calendarioCriacao?></p>
							<hr>
							<p><b>Id Transação:</b> <?=$cookie_IdTrans?></p>
							<hr>
							<p><b>NSU:</b> <?=$cookie_NSU?></p>
							<hr>
							<p><b>Autenticação:</b> <?=$cookie_txid?></p>
							
                            <p style="text-align:center" class="pb-30"><a href="assinaturas" class="btn main-btn main-btn-lg main-btn-primary">Minhas assinaturas</a></p>
                        </div>
					
					<?php } if($dataHoraAtual > $expiracaoDateTime){ ?>
					
						<h3 style="text-align:center">Cobrança inválida!</h3>
                        <div class='alert alert-danger'><small>Lamentamos informar que o pagamento foi cancelado devido ao vencimento do prazo da cobrança. Neste momento, não é mais possível utilizar essa transação para compra de uma nova assinatura.</small></div>
                        <p style="text-align:center"><a href="assinaturas&New=TipoPagamento" class="btn main-btn main-btn-lg main-btn-primary">Tentar novamente</a></p>
						<p>Escolha uma das opções abaixo para pagar o PIX em seu banco e após efetuar o pagamento, aguarde o sistema identificar e ativar sua assinatura automaticamente.</p>
						<div style="text-align:center" class="form-group mb-15"><label class="form-label">Pix QrCode</label><br><img style="width:200px; height:200px;" src="https://cdn-icons-png.flaticon.com/512/6797/6797387.png" alt="about"></div>
						<div style="text-align:center" class="form-group mb-15">
                            <label class="form-label">Pix copia e cola</label>
                            <textarea class="form-control" rows="4" placeholder="<?=$qrcode?>" readonly></textarea>
                        </div>
					
					<?php } ?>
                    </div>
                </div>
				
				<!-- INICIO DA INSERÇÃO DE COBRANÇA VIA SALDO EM CONTA NO BANCO DE DADOS -->
				
				<?php
				elseif ($NewSign == "EndPaySaldo" AND $assinaturas['total'] < 30):
					$TxId = $_GET['TxId'] ?? null;
				
				// Conexão com o banco de dados
					$conexao = conexao::getInstance();

					// Chama a session da id do usuário pego no login para armazenar na tabela
					$cookie_Uid = $_SESSION["DocID"];

					// Verifica se já existe um registro com o mesmo txidAPI
					$sql = "SELECT * FROM assinaturas WHERE txidAPI = :txidAPI";
					$stm = $conexao->prepare($sql);
					$stm->bindParam(':txidAPI', $TxId, PDO::PARAM_STR);
					$stm->execute();
					// Obtém o número de linhas
					$resultado = $stm->rowCount();
						
					// Consulta o registro mais antigo com Status igual a 1
					$sql = "SELECT * FROM assinaturas WHERE Status = 1 ORDER BY id LIMIT 1";
					$stm = $conexao->prepare($sql);
					$stm->execute();
					$assinatura = $stm->fetch(PDO::FETCH_OBJ);
						
					if ($resultado == 0) {
						// Se não existe, insere um novo registro
						$sql = 'INSERT INTO assinaturas (Uid, NSU, IdTransacao, NumeroSorte, Status, txidAPI)
								VALUES (:Uid, :NSU, :IdTransacao, :NumeroSorte, 1, :txidAPI)';

						$stm = $conexao->prepare($sql);
						$stm->bindParam(':Uid', $cookie_Uid, PDO::PARAM_STR);
						$stm->bindParam(':NSU', $cookie_NSU, PDO::PARAM_STR);
						$stm->bindParam(':IdTransacao', $cookie_IdTrans, PDO::PARAM_STR);
						$stm->bindParam(':NumeroSorte', $cookie_numero, PDO::PARAM_STR);
						$stm->bindParam(':txidAPI', $TxId, PDO::PARAM_STR);
						$stm->execute();
						
						// Faz a subtração do valor da compra da assinatura no saldo
						$sql = "UPDATE users SET SaldoTotal = SaldoTotal - 120.00 WHERE id = :idUsuario";
						$stm = $conexao->prepare($sql);
						$stm->bindParam(':idUsuario', $_SESSION["DocID"], PDO::PARAM_INT);
						$stm->execute();
								
						// Consulta o amigo indicador para bonificar
						foreach ($users as $user):
							$IndicadoPor = $user->AmigoIndicador;
							$BonusPago = $user->BonusPago;

								if ($BonusPago == "Nao") {
									// Pague o bônus de R$ 5,00 para o amigo indicador
									$bonus_valor = 5.00;

								// Atualiza o saldo do amigo indicador
								$sql = "UPDATE users SET SaldoTotal = SaldoTotal + :bonus_valor WHERE id = :idIndicador";
								$stm = $conexao->prepare($sql);
								$stm->bindParam(':bonus_valor', $bonus_valor, PDO::PARAM_STR);
								$stm->bindParam(':idIndicador', $IndicadoPor, PDO::PARAM_INT);
								$stm->execute();

								// Atualiza o bônus para "Sim"
								$sql = "UPDATE users SET BonusPago = 'Sim' WHERE id = :idUsuario";
								$stm = $conexao->prepare($sql);
								$stm->bindParam(':idUsuario', $_SESSION["DocID"], PDO::PARAM_INT);
								$stm->execute();
							}
						endforeach;
								
							// Verifica se ContadorGanhos é igual a 1 ou 2
							if ($assinatura->ContadorGanhos == 0 || $assinatura->ContadorGanhos == 1) {
								
								// Adiciona +1 ao ContadorGanhos
								$contadorGanhosAtualizado = $assinatura->ContadorGanhos + 1;

								// Atualiza o valor de ContadorGanhos no banco de dados
								$sqlUpdate = "UPDATE assinaturas SET ContadorGanhos = :ContadorGanhos WHERE id = :id";
								$stmUpdate = $conexao->prepare($sqlUpdate);
								$stmUpdate->bindParam(':ContadorGanhos', $contadorGanhosAtualizado, PDO::PARAM_INT);
								$stmUpdate->bindParam(':id', $assinatura->id, PDO::PARAM_INT);
								$stmUpdate->execute();
								
								// Valor a ser adicionado ao saldo do usuário
								$ValorAddBlock = 60;

								// Atualiza o valor de saldo do usuário no banco de dados
								$UserSqlUpdate = "UPDATE users SET SaldoBloqueado = SaldoBloqueado + :ValorAddBlock WHERE id = :id";
								$stmUserUpdate = $conexao->prepare($UserSqlUpdate);
								$stmUserUpdate->bindParam(':ValorAddBlock', $ValorAddBlock, PDO::PARAM_INT);
								$stmUserUpdate->bindParam(':id', $assinatura->Uid, PDO::PARAM_STR);
								$stmUserUpdate->execute();
							}
						}

						// Verifica se ContadorGanhos é igual a 3 após a atualização
						if ($assinatura->ContadorGanhos == 2) {
								
							// Valor a ser adicionado ao saldo do usuário
							$valorAdicionado = 180;
								
							// Valor a ser adicionado ao saldo do usuário
							$ValorAddBlock = 0;

							// Atualiza o valor de saldo do usuário no banco de dados
							$UserSqlUpdate = "UPDATE users SET SaldoTotal = SaldoTotal + :valorAdicionado, SaldoBloqueado = :ValorAddBlock WHERE id = :id";
							$stmUserUpdate = $conexao->prepare($UserSqlUpdate);
							$stmUserUpdate->bindParam(':valorAdicionado', $valorAdicionado, PDO::PARAM_INT);
							$stmUserUpdate->bindParam(':ValorAddBlock', $ValorAddBlock, PDO::PARAM_INT);
							$stmUserUpdate->bindParam(':id', $assinatura->Uid, PDO::PARAM_STR);
							$stmUserUpdate->execute();
								
							// Insere os dados na tabela assinaturasconcluidas
							$sqlInsert = "INSERT INTO assinaturasconcluidas (Uid, NSU, IdTransacao, NumeroSorte, DataAssinatura, ContadorGanhos, Status, txidAPI)
												VALUES (:Uid, :NSU, :IdTransacao, :NumeroSorte, :DataAssinatura, :ContadorGanhos, :Status, :txidAPI)";
							$stmInsert = $conexao->prepare($sqlInsert);
							$stmInsert->bindParam(':Uid', $assinatura->Uid, PDO::PARAM_STR);
							$stmInsert->bindParam(':NSU', $assinatura->NSU, PDO::PARAM_STR);
							$stmInsert->bindParam(':IdTransacao', $assinatura->IdTransacao, PDO::PARAM_STR);
							$stmInsert->bindParam(':NumeroSorte', $assinatura->NumeroSorte, PDO::PARAM_STR);
							$stmInsert->bindParam(':DataAssinatura', $assinatura->DataAssinatura, PDO::PARAM_STR);
							$contadorGanhosInsert = 3;
							$stmInsert->bindParam(':ContadorGanhos', $contadorGanhosInsert, PDO::PARAM_INT);
							$stmInsert->bindParam(':Status', $assinatura->Status, PDO::PARAM_INT);
							$stmInsert->bindParam(':txidAPI', $assinatura->txidAPI, PDO::PARAM_STR);
							$stmInsert->execute();

							// Apaga o registro da tabela assinaturas
							$sqlDelete = "DELETE FROM assinaturas WHERE id = :id";
							$stmDelete = $conexao->prepare($sqlDelete);
							$stmDelete->bindParam(':id', $assinatura->id, PDO::PARAM_INT);
							$stmDelete->execute();
						}
				
				?>
				
				<div class="about-section pb-15">
                    <div class="text-details">
						
						<div class="alert alert-primary" role="alert">
							<div style="text-align:center"><img src="https://cdn-icons-png.flaticon.com/512/11338/11338402.png" width="150" height="150" alt="" title="" class="img-small"></div>
                            <h4 style="text-align:center" class="alert-heading">Pagamento identificado com sucesso!</h4>
                            <p>Agradecemos pelo seu pagamento, o qual foi prontamente identificado. Informamos que a sua assinatura de rendimento foi ativada com êxito, proporcionando um retorno de R$ 60,00. Você poderá efetuar o saque em 4 dias após a disponibilidade.</p>
                            
							<h4 class="alert-heading">Informações da assinatura</h4>
							<p><b>Status:</b> Ativa</p>
							<hr>
							<p><b>Data e hora:</b> <?=$cookie_calendarioCriacao?></p>
							<hr>
							<p><b>Id Transação:</b> <?=$cookie_IdTrans?></p>
							<hr>
							<p><b>NSU:</b> <?=$cookie_NSU?></p>
							<hr>
							<p><b>Autenticação:</b> <?=$TxId?></p>
							
                            <p style="text-align:center" class="pb-30"><a href="assinaturas" class="btn main-btn main-btn-lg main-btn-primary">Minhas assinaturas</a></p>
                        </div>
						
                    </div>
                </div>
				
				<?php
				else:
				if ($opensign === null AND $assinaturas['total'] < 30): ?>
					
					<!-- Tab-selector -->
                <div class="tab-selector">
                    <!-- Tab-selector-list -->
                    <ul class="tab-selector-list">
                        <li class="active" data-tab-list="1">
                            <button>Ativas</button>
                        </li>
						<li>
							<button type="button" class="btn main-btn main-btn-light"  data-bs-toggle="modal" data-bs-target="#confirmPayment">+ Nova assinatura</button>
						</li>
                    </ul>
                    <!-- Tab-selector-list -->

						<?php
						$conexao = conexao::getInstance();
						$sql = "SELECT * FROM assinaturas WHERE Uid = " . $_SESSION["DocID"] . " AND Status = 1 ORDER BY id ASC";
						$stm = $conexao->prepare($sql);
						$stm->execute();
						$TodasAssin = $stm->fetchAll(PDO::FETCH_OBJ);

						if (!empty($TodasAssin)) {
							foreach ($TodasAssin as $assinatura) {
								?>
								<!-- Tab-selector-details -->
								<div class="tab-selector-details">
									<div class="tab-selector-details-item active" data-tab-details="1">
										<div class="row gx-3">
											<div onclick="location.href='assinaturas&NSU=<?= $assinatura->NSU ?>'" class="col-12 pb-15">
												<div class="monthly-bill-card monthly-bill-card-green">
													<div class="monthly-bill-body">
														<h3 style="text-align: center; padding-bottom:10px">Assinatura N° <?= $assinatura->NSU ?></h3>
														<p>Data da assinatura: <?= date('d/m/Y - H:i', strtotime($assinatura->DataAssinatura)) ?><br> Posição da assinatura: <?= $assinatura->id ?> <br> Número da sorte: <?= $assinatura->NumeroSorte ?></p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php
							}
						} else { ?>
							<div class="notification-item">
								<div class="notification-card notificationx mb-15">
									<a href="#">
										<div class="notification-card-thumb">
											<i class="flaticon-bell"></i>
										</div>
										<div class="notification-card-details">
											<h3>Nenhuma assinatura!</h3>
											<p>Você não tem assinaturas ativas no momento.</p>
										</div>
									</a>
								</div>
							</div>
						<?php } ?>
                    <!-- Tab-selector-details -->
                </div>
                <!-- Tab-selector -->
				
				<?php else:
							echo "<p>Informações não encontradas.</p>";
						endif;
					endif; ?>
            </div>
        </div>
		
		<!-- Confirm-payment-modal -->
        <div class="modal fade" id="confirmPayment" tabindex="-1" aria-labelledby="confirmPayment" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-animatezoom">
                <div class="modal-content">
                    <div class="container">
                        <div class="modal-header">
                            <div class="modal-header-title">
                                <h5 class="modal-title">Compra de assinatura</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-center">
                            <form class="text-center">
                                <h3>Importante!</h3>
								<p>Para prosseguir com a compra de uma nova assinatura de bonificação, é necessário que você confirme ter lido e concordado com os termos de uso da plataforma RapidMoney.</p>
                                <a href="assinaturas&New=TipoPagamento" class="btn main-btn main-btn-lg full-width">Confirmar e prosseguir!</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
require __DIR__ . '/../frwk/vendor/autoload.php';
include '../conexao.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

// 1. Obter todos os grupos de cuidado existentes
$sqlGruposCuidado = "SELECT id FROM grupo_cuidado";
$resultGruposCuidado = $conn->query($sqlGruposCuidado);

if ($resultGruposCuidado->num_rows > 0) {
    while ($grupo = $resultGruposCuidado->fetch_assoc()) {
        $id_grupoCuidado = $grupo['id'];

        // 2. Para cada grupo, buscar os usuários associados
        $sqlUsuarios = "SELECT id, nome, tokenFcm FROM usuarios WHERE id_grupoCuidado = '$id_grupoCuidado'";
        $resultUsuarios = $conn->query($sqlUsuarios);
        $usuarios = [];

        if ($resultUsuarios->num_rows > 0) {
            while ($usuario = $resultUsuarios->fetch_assoc()) {
                $usuarios[] = $usuario; // Lista de usuários do grupo
            }
        } else {
            continue; // Não há usuários ativos neste grupo, pula para o próximo
        }

        // 3. Verificar se há remédios programados para os próximos 5 minutos
        $sqlRemedios = "SELECT
                            t1.id,
                            t1.id_remedio,
                            t1.id_pessoa,
                            t1.dosagem_pessoa,
                            t1.tipo_dosagem_pessoa,
                            t2.hora_dia,
                            t2.frequencia,
                            p.nome,
                            r.nome as nome_remedio
                        FROM
                            pessoa_has_remedio t1
                        JOIN pessoaRemedio_has_programacao t2 ON
                            t1.id = t2.id_pessoaRemedio
                        JOIN pessoa p ON
                            t1.id_pessoa = p.id
                        JOIN remedios r ON
                            t1.id_remedio = r.id
                        WHERE
                            p.user_id IN (SELECT id FROM usuarios WHERE id_grupoCuidado = '$id_grupoCuidado')
                            AND DATE_FORMAT(t2.hora_dia, '%H:%i:%s') BETWEEN DATE_FORMAT(NOW(), '%H:%i:%s') AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 5 MINUTE), '%H:%i:%s')
                        ORDER BY
                            t2.hora_dia";

        $resultRemedios = $conn->query($sqlRemedios);

        if ($resultRemedios->num_rows > 0) {
            $remedios_programados = [];
            while ($remedio = $resultRemedios->fetch_assoc()) {
                $remedios_programados[] = $remedio;
            }
            // 4. Enviar notificações para todos os usuários do grupo
            foreach ($usuarios as $usuario) {
                $token = $usuario['tokenFcm'];
                $nome_usuario_grupo = explode(' ', $usuario['nome'])[0];
                foreach ($remedios_programados as $remedio) {
                    // Pega o primeiro nome do campo 'nome' do array atual
                    $nome_usuario = explode(' ', $remedio['nome'])[0]; 
                    $nome_remedio = $remedio['nome_remedio'];
        
                    sendFirebaseNotification($token, "Atenção $nome_usuario", "Você tem que tomar $nome_remedio");
                    echo "Notificação enviada para $nome_usuario_grupo sobre $nome_remedio." . PHP_EOL;
                }
            }
        } else {
            echo "Nenhum remédio programado para os próximos 5 minutos no grupo $id_grupoCuidado." . PHP_EOL;
        }
    }
} else {
    echo "Nenhum grupo de cuidado encontrado." . PHP_EOL;
}
echo "Executado em: " . date('r') . PHP_EOL . str_repeat('-', 50) . PHP_EOL;

function sendFirebaseNotification($token, $title, $body) {
    try {
        // Carregue as credenciais do Firebase
        $factory = (new Factory)->withServiceAccount('serviceAccountKey.json');
        $messaging = $factory->createMessaging();

        // Crie a mensagem push
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData([ 'screen' => 'home_page' ]);

        // Envie a mensagem push
        $messaging->send($message);

        echo "Notificação enviada com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao enviar notificação: " . $e->getMessage();
    }
}
?>

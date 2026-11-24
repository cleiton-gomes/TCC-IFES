
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;

$server = '10.0.0.5';
$port = 1883;
$clientId = 'iot-edge-command-center-consumer-test';

$topic = 'iot-edge/dispositivos/+/dados';

try {
    $mqtt = new MqttClient($server, $port, $clientId);

    echo "============================================\n";
    echo " IoT-Edge Command Center - MQTT Consumer\n";
    echo "============================================\n";
    echo "Broker: {$server}:{$port}\n";
    echo "Topico: {$topic}\n";
    echo "Conectando...\n";

    $mqtt->connect(null, true);

    echo "Conexao MQTT estabelecida.\n";
    echo "Aguardando mensagens...\n\n";

    $mqtt->subscribe(
        $topic,
        function (
            string $receivedTopic,
            string $message,
            bool $retained,
            array $matchedWildcards
        ): void {
            echo "--------------------------------------------\n";
            echo 'Recebido em: ' . date('d/m/Y H:i:s') . "\n";
            echo "Topico: {$receivedTopic}\n";
            echo 'Retida: ' . ($retained ? 'Sim' : 'Nao') . "\n";

            $data = json_decode($message, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                echo "Payload JSON:\n";
                echo json_encode(
                    $data,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                ) . "\n";
            } else {
                echo "Payload recebido (JSON invalido):\n";
                echo $message . "\n";
            }

            flush();
        },
        0
    );

    $mqtt->loop(true);

    if ($mqtt->isConnected()) {
        $mqtt->disconnect();
    }

} catch (Throwable $e) {
    fwrite(
        STDERR,
        "\nERRO NO CONSUMER MQTT: " . $e->getMessage() . "\n"
    );

    exit(1);
}

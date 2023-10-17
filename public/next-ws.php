<?php

/**
 * Its will be used to keep framework alive until the pages reload. Will be used to get instant feedback from components and atached events
 */


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface as WebSocketMessageComponentInterface;
use Ratchet\WebSocket\WsServer;

require 'vendor/autoload.php';

class NextWS implements WebSocketMessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        // Implementação quando uma conexão é aberta
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Implementação quando uma mensagem é recebida
        $from->send("Resposta: $msg");
    }

    public function onClose(ConnectionInterface $conn) {
        // Implementação quando uma conexão é fechada
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Implementação para lidar com erros
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}


$server = \Ratchet\Server\IoServer::factory(
    new WsServer(new NextWs()),
    8080
);

$server->run();
echo '<html><head>';
echo '<meta http-equiv="Content-Security-Policy" content="default-src \'self\'; connect-src \'self\' ws://localhost wss://localhost*;">';
echo '</head><body><h3>WebSocket</h3></body></html>';
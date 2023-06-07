<?php

namespace app\commands;

use app\components\WebSocketComponent;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use yii\console\Controller;

class WebSocketServerCommand extends Controller
{
    public function actionStart($port = 8081)
    {
        $webSocketComponent = WebSocketComponent::singleton();

        $server = IoServer::factory(
            new HttpServer(
                new WsServer($webSocketComponent)
            ),
            $port
        );

        $webSocketComponent->setServer($server);

        echo "Server started on port {$port}\n";
        $server->run();
    }
}

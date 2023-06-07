<?php

namespace app\modules\bartender\jobs;

use app\components\WebSocketComponent;
use yii\base\BaseObject;
use yii\queue\Queue;
use Yii;

class MakingDrinkJob extends BaseObject implements \yii\queue\JobInterface
{
    public function execute($queue)
    {
        // Access the WebSocket server instance
        $webSocketServer = WebSocketComponent::singleton(); // Yii::$app->get('webSocketServer');

        // Send the message to the WebSocket server
        $webSocketServer->sendMessage('Order is done');
    }
}

<?php

namespace app\components;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;

class WebSocketComponent implements MessageComponentInterface
{
    protected $connections;
    protected $server;
    protected $connecton;

    // Hold an instance of the class
    private static $instance;

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }
    private function __construct()
    {
        $this->connections = new \SplObjectStorage;
    }

    public function setServer(IoServer $server)
    {
        $this->server = $server;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->connections->attach($conn);
        $this->connecton = $conn;

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Broadcast the message to all connections
        foreach ($this->connections as $conn) {
            if ($conn !== $from) {
                $conn->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remove the connection
        $this->connections->detach($conn);
        echo "Connection closed! ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function sendMessage($message)
    {
        foreach ($this->connections as $client) {
            $client->send($message);
        }
    }
}

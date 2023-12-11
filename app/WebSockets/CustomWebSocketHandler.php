<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\WebSockets;

use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

/**
 * Custom Web Socket Handler
 *
 * @package \App\WebSockets
 */
class CustomWebSocketHandler extends WebSocketHandler implements MessageComponentInterface
{
    /**
     * @param ConnectionInterface $connection
     * @return void
     */
    public function onOpen(ConnectionInterface $connection) : void
    {
        parent::onOpen($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @return void
     */
    public function onClose(ConnectionInterface $connection) : void
    {
        parent::onClose($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @param \Exception          $exception
     * @return void
     */
    public function onError(ConnectionInterface $connection, \Exception $exception) : void
    {
        parent::onError($connection, $exception);
    }

    /**
     * @param ConnectionInterface $connection
     * @param MessageInterface    $message
     * @return void
     */
    public function onMessage(ConnectionInterface $connection, MessageInterface $message) : void
    {
        parent::onMessage($connection, $message);
    }
}

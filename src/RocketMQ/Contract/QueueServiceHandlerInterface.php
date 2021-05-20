<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Contract;

use MQ\Model\Message;

interface QueueServiceHandlerInterface
{
    public function handler(Message $message): bool;

    public function failure(Message $message): void;
}

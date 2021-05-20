<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Tests\Feature\Handler;

use MQ\Model\Message;
use Samego\RocketMQ\Contract\QueueServiceHandlerInterface;
use Samego\RocketMQ\Helper\StdLogHelper;

class TrainingControllerHandler implements QueueServiceHandlerInterface
{
    public function handler(Message $message): bool
    {
        StdLogHelper::info('message : ' . json_encode($message));

        return true;
    }

    public function failure(Message $message): void
    {
    }
}

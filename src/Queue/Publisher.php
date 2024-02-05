<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Queue;

use Nayleen\Async\Bus\Message;

interface Publisher
{
    public function publish(Queue $queue, Message $message): void;
}

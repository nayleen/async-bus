<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Queue\Worker;

use Nayleen\Async\Bus\Queue\Consumer as QueueConsumer;
use Nayleen\Async\Bus\Queue\Queue;
use Nayleen\Async\Kernel;
use Nayleen\Async\Timers;
use Nayleen\Async\Worker;

readonly class Consumer extends Worker
{
    public function __construct(private QueueConsumer $consumer, private Queue $queue)
    {
        parent::__construct(
            fn (Kernel $kernel) => $this->consumer->consume($this->queue, $kernel->cancellation),
            new Timers(),
        );
    }
}

<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Queue\Middleware;

use Closure;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Middleware\Middleware;
use Nayleen\Async\Bus\Queue\Publisher;
use Nayleen\Async\Bus\Queue\Queue;
use Nayleen\Async\Bus\Queue\QueueMap;
use OutOfBoundsException;

readonly class PublishesToConfiguredQueueMiddleware implements Middleware
{
    public function __construct(
        private Publisher $publisher,
        private QueueMap $queueMap,
        private ?Queue $fallback = null,
    ) {}

    public function handle(Message $message, Closure $next): void
    {
        try {
            $queue = $this->queueMap->queue($message);
        } catch (OutOfBoundsException $ex) {
            if (!isset($this->fallback)) {
                throw $ex;
            }

            $queue = $this->fallback;
        }

        $this->publisher->publish($queue, $message);
        $next($message);
    }
}

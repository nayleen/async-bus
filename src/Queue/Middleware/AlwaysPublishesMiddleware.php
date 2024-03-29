<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Queue\Middleware;

use Closure;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Middleware\Middleware;
use Nayleen\Async\Bus\Queue\Publisher;
use Nayleen\Async\Bus\Queue\Queue;

readonly class AlwaysPublishesMiddleware implements Middleware
{
    public function __construct(
        private Publisher $publisher,
        private Queue $queue,
    ) {}

    public function handle(Message $message, Closure $next): void
    {
        $this->publisher->publish($this->queue, $message);
        $next($message);
    }
}

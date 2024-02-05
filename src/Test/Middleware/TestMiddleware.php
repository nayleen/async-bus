<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Test\Middleware;

use Closure;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Middleware\Middleware;

/**
 * @psalm-internal Nayleen\Async
 */
final readonly class TestMiddleware implements Middleware
{
    public function __construct(
        private Results $results,
        private int $expectedIndex,
    ) {}

    public function handle(Message $message, Closure $next): void
    {
        $this->results->list[] = $this->expectedIndex;
        $next($message);
    }
}

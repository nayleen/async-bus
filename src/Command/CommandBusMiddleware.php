<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Command;

use Closure;
use Monolog\Level;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Middleware\Middleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

readonly class CommandBusMiddleware implements Middleware
{
    public function __construct(
        private CommandHandlers $handlers,
        private LoggerInterface $logger = new NullLogger(),
        private int|Level|string $level = LogLevel::DEBUG,
    ) {}

    /**
     * @param Closure(Message): void $next
     */
    public function handle(Message $message, Closure $next): void
    {
        $handler = $this->handlers->find($message);

        $this->logger->log($this->level, 'Started executing command handler', ['command' => $message]);
        $handler($message);
        $this->logger->log($this->level, 'Finished executing command handler', ['command' => $message]);

        $next($message);
    }
}

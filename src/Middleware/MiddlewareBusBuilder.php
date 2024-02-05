<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Middleware;

use Monolog\Level;
use Nayleen\Async\Bus\Command\CommandBusMiddleware;
use Nayleen\Async\Bus\Command\CommandHandlers;
use Nayleen\Async\Bus\Event\EventBusMiddleware;
use Nayleen\Async\Bus\Event\EventHandlers;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * @template-covariant TBus of MiddlewareBus
 */
readonly class MiddlewareBusBuilder
{
    /**
     * @param class-string<TBus> $class
     */
    public function __construct(
        private string $class = MiddlewareBus::class,
        private LoggerInterface $logger = new NullLogger(),
        private int|Level|string $level = LogLevel::DEBUG,
    ) {}

    /**
     * @return TBus
     */
    public function command(CommandHandlers $handlers = new CommandHandlers()): MiddlewareBus
    {
        return new ($this->class)(
            new LoggingMiddleware($this->logger, $this->level),
            new CommandBusMiddleware($handlers, $this->logger, $this->level),
        );
    }

    /**
     * @return TBus
     */
    public function event(EventHandlers $handlers = new EventHandlers()): MiddlewareBus
    {
        return new ($this->class)(
            new LoggingMiddleware($this->logger, $this->level),
            new EventBusMiddleware($handlers, $this->logger, $this->level),
        );
    }
}

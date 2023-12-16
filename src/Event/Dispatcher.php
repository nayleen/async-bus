<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Event;

use Closure;
use LogicException;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Middleware\Middleware;
use Nayleen\Async\Bus\Middleware\MiddlewareBus;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * An abstraction over a regular event bus allowing customization of its runtime behavior by:
 * - appending and prepending middleware
 * - adding event handlers
 */
class Dispatcher
{
    private readonly MiddlewareBus $eventBus;

    public function __construct(
        private readonly EventHandlers $eventHandlers = new EventHandlers(),
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
        $this->eventBus = new MiddlewareBus(new EventBusMiddleware($this->eventHandlers, $this->logger));
    }

    /**
     * Appends a middleware to the event bus,
     * making it the last middleware to be executed.
     *
     * @throws LogicException if the event bus has been finalized, e.g. after the first call to `handle()`
     */
    public function after(Middleware $middleware): void
    {
        $this->eventBus->append($middleware);
    }

    /**
     * Prepends a middleware to the event bus,
     * making it the first middleware to be executed.
     *
     * @throws LogicException if the event bus has been finalized, e.g. after the first call to `handle()`
     */
    public function before(Middleware $middleware): void
    {
        $this->eventBus->prepend($middleware);
    }

    public function handle(Message $message): void
    {
        $this->eventBus->handle($message);
    }

    /**
     * @param non-empty-string $event
     * @param Closure(Message): void $handler
     */
    public function listen(string $event, Closure $handler): void
    {
        $this->eventHandlers->add($event, $handler);
    }
}

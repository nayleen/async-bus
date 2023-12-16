<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Event;

use Amp\PHPUnit\AsyncTestCase;
use Closure;
use Nayleen\Async\Bus\Message;
use Nayleen\Async\Bus\Test\Middleware\Results;
use Nayleen\Async\Bus\Test\Middleware\TestMiddleware;

/**
 * @internal
 */
final class DispatcherTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function can_add_listeners(): void
    {
        $eventHandlers = $this->createMock(EventHandlers::class);
        $eventHandlers->expects(self::once())->method('add')->with('test', self::isInstanceOf(Closure::class));

        $dispatcher = new Dispatcher($eventHandlers);
        $dispatcher->listen('test', fn (Message $message) => null);
    }

    /**
     * @test
     */
    public function can_append_middlewares(): void
    {
        $dispatcher = new Dispatcher();

        $results = new Results();
        $dispatcher->after(new TestMiddleware($results, 1));

        $message = $this->createMock(Message::class);
        $message->method('name')->willReturn('test');

        $dispatcher->handle($message);
        self::assertCount(1, $results->list);
    }

    /**
     * @test
     */
    public function can_prepend_middlewares(): void
    {
        $dispatcher = new Dispatcher();

        $results = new Results();
        $dispatcher->before(new TestMiddleware($results, 1));

        $message = $this->createMock(Message::class);
        $message->method('name')->willReturn('test');

        $dispatcher->handle($message);
        self::assertCount(1, $results->list);
    }
}

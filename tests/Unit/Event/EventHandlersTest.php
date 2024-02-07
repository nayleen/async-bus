<?php

declare(strict_types = 1);

namespace Nayleen\Async\Bus\Event;

use Amp\PHPUnit\AsyncTestCase;
use Nayleen\Async\Bus\Message;

/**
 * @internal
 */
final class EventHandlersTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function can_merge_with_other_instances(): void
    {
        $name = 'event';

        $empty = new EventHandlers();
        $other = new EventHandlers([$name => [fn (Message $message) => null]]);

        $message = $this->createMock(Message::class);
        $message->method('name')->willReturn($name);

        $merged = $empty->merge($other);
        self::assertCount(1, $merged->filter($message));
    }
}

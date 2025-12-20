<?php

declare(strict_types=1);

namespace App\Tests\Domain\Events;

use App\Domain\Events\EventDispatcher;
use PHPUnit\Framework\TestCase;

final class SpyEventDispatcher implements EventDispatcher
{
    /** @var array<int, mixed> */
    private array $dispatchedEvents = [];

    public function dispatch(mixed $event): void
    {
        $this->dispatchedEvents[] = $event;
    }

    /**
     * Asserts that only one type of event has been triggered.
     *
     * @template T
     *
     * @param class-string<T> $expectedType
     * @param int             $count        specifies how many times an event should have been triggered
     *
     * @return T|T[]
     */
    public function assertOnlyEventDispatched(string $expectedType, TestCase $testCase, int $count = 1): mixed
    {
        $testCase->assertCount($count, $this->dispatchedEvents);
        foreach ($this->dispatchedEvents as $event) {
            $testCase->assertEquals($expectedType, $event::class, 'Wrong event dispatched');
        }

        if (1 == $count) {
            return $this->dispatchedEvents[0];
        } else {
            return $this->dispatchedEvents;
        }
    }

    /**
     * Asserts that an event has been triggered.
     *
     * @template T
     *
     * @param class-string<T> $expectedType
     * @param int             $count        specifies how many times an event should have been triggered
     *
     * @return T|T[]
     */
    public function assertEventDispatched(string $expectedType, TestCase $testCase, int $count = 1): mixed
    {
        $events = array_filter($this->dispatchedEvents, fn ($e) => $e::class == $expectedType);
        $testCase->assertCount($count, $events, 'Event has not been triggered as many times as expected');

        if (1 == $count) {
            return $events[0];
        } else {
            return $events;
        }
    }

    /**
     * @param array<int,mixed> $expectedEvents
     */
    public function assertEventsContain(array $expectedEvents, TestCase $testCase): void
    {
        foreach ($expectedEvents as $expected) {
            $testCase->assertContainsEquals($expected, $this->dispatchedEvents, 'Event has not been triggered');
        }
    }
}

<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

readonly class MoveEvent
{
    public function __construct(public string $eventId,
                                public \DateTimeImmutable $newDate,
                                public bool $sendUpdates = false)
    {
    }
}
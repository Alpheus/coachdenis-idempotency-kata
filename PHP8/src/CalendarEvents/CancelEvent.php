<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

readonly class CancelEvent
{
    public function __construct(public string $eventId,
                                public bool $sendUpdates = false)
    {
    }
}
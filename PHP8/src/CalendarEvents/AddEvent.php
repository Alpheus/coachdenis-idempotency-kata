<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

readonly class AddEvent
{
    public function __construct(public \DateTimeImmutable $datetime,
                                public string $name) {}
}
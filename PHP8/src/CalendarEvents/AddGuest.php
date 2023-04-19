<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

readonly class AddGuest
{
    public function __construct(public string $eventId,
                                public Calendar $guest,
                                public bool $sendUpdates = false)
    {
    }
}
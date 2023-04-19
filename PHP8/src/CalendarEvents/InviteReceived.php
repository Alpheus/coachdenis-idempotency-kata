<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

readonly class InviteReceived
{
    public function __construct(public Calendar $from,
                                public AddEvent $invitedTo,
                                public string $eventId)
    {
    }
}
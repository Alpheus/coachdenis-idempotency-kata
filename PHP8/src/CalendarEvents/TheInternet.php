<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

class TheInternet
{
    /**
     * @var iterable<Calendar>
     */
    protected array $calendars = [];

    public function connect(Calendar $c): void
    {
        $this->calendars[] = $c;
        $c->connect($this);
    }

    public function send(Calendar $from, Calendar $to, mixed $message): iterable
    {
        $matches = array_filter($this->calendars, fn (Calendar $c) => $c === $to);

        return array_map(fn (Calendar $c) => $c->newMessage($from, $message), $matches);
    }
}
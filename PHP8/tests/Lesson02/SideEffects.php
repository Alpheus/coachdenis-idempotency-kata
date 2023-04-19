<?php

namespace Tests\Lesson02;

use Coachdenis\IdempotencyKata\CalendarEvents\AddEvent;
use Coachdenis\IdempotencyKata\CalendarEvents\AddGuest;
use Coachdenis\IdempotencyKata\CalendarEvents\Calendar;
use Coachdenis\IdempotencyKata\CalendarEvents\CancelEvent;
use Coachdenis\IdempotencyKata\CalendarEvents\MoveEvent;
use Coachdenis\IdempotencyKata\CalendarEvents\TheInternet;
use Coachdenis\IdempotencyKata\CalendarEvents\YourCalendarClass;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SideEffects extends TestCase
{
    /**
     * @var array<Calendar>
     */
    protected array $calendars;

    protected TheInternet $internet;

    const ALICE = 'alice@email.com';
    const BOB = 'bob@bob.com';
    const RAMAPATRIKUNA = 'crazyrama@yahoo.in';

    /**
     * @todo Start here, remove this function.
     */
    #[Before]
    protected function Instructions(): void
    {
        $this->markTestSkipped();
    }

    #[Before]
    protected function TheInternet(): void
    {
        $this->internet = new TheInternet();

        foreach ($this->calendars as $calendar)
            $this->internet->connect($calendar);
    }

    #[Before]
    protected function Calendars(): void
    {
        // @TODO your classes here
        $this->calendars = [
            self::RAMAPATRIKUNA => new YourCalendarClass(self::RAMAPATRIKUNA),
            self::ALICE => new YourCalendarClass(self::ALICE),
            self::BOB => new YourCalendarClass(self::BOB),
        ];
    }

    #[Test]
    public function MovingEvents_ShouldNot_LeaveDuplicates()
    {
        $d = new \DateTimeImmutable("2023-04-20 11:00:00+02:00");
        $eventId = $this->calendars[self::ALICE]->newEvent(new AddEvent($d, "Denis Time!"));

        Assert::assertCount(1, $this->calendars[self::ALICE]->listEvents('2023-04-20'));

        $d2 = new \DateTimeImmutable("2023-04-27 11:00:00+02:00");
        $this->calendars[self::ALICE]->modifyEvent(new MoveEvent($eventId, $d2));

        Assert::assertCount(0, $this->calendars[self::ALICE]->listEvents('2023-04-20'));
        Assert::assertCount(1, $this->calendars[self::ALICE]->listEvents('2023-04-27'));
    }

    #[Test]
    public function Inviting_AGuest_Should_AddEvent_To_TheirCalendarAndInbox()
    {
        $d = new \DateTimeImmutable("2023-04-20 11:00:00+02:00");
        $eventId = $this->calendars[self::ALICE]->newEvent(new AddEvent($d, "Denis Time!"));

        Assert::assertCount(1, $this->calendars[self::ALICE]->listEvents('2023-04-20'));

        Assert::assertCount(0, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-20'));
        $this->calendars[self::ALICE]->modifyEvent(new AddGuest($eventId, $this->calendars[self::RAMAPATRIKUNA], true));

        Assert::assertCount(1, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-20'));
    }

    #[Test]
    public function CancelledEvents_Should_NotAppear_OnList()
    {
        $d = new \DateTimeImmutable("2023-04-20 11:00:00+02:00");
        $eventId = $this->calendars[self::BOB]->newEvent(new AddEvent($d, "Denis Time!"));

        Assert::assertCount(1, $this->calendars[self::BOB]->listEvents('2023-04-20'));

        $this->calendars[self::BOB]->modifyEvent(new CancelEvent($eventId));

        Assert::assertCount(0, $this->calendars[self::BOB]->listEvents('2023-04-20'));
    }

    #[Test]
    public function CancelledEvents_SharedWithOthers_ShouldNot_Leave_DanglingInvites()
    {
        $d = new \DateTimeImmutable("2023-04-20 11:00:00+02:00");
        $eventId = $this->calendars[self::ALICE]->newEvent(new AddEvent($d, "Denis Time!"));

        Assert::assertCount(1, $this->calendars[self::ALICE]->listEvents('2023-04-20'));

        Assert::assertCount(0, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-20'));
        $this->calendars[self::ALICE]->modifyEvent(new AddGuest($eventId, $this->calendars[self::RAMAPATRIKUNA], true));

        Assert::assertCount(1, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-20'));

        $d2 = new \DateTimeImmutable("2023-04-27 11:00:00+02:00");
        $this->calendars[self::ALICE]->modifyEvent(new MoveEvent($eventId, $d2, true));

        Assert::assertCount(0, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-20'));
        Assert::assertCount(1, $this->calendars[self::RAMAPATRIKUNA]->listEvents('2023-04-27'));
    }
}
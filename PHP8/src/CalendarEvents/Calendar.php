<?php

namespace Coachdenis\IdempotencyKata\CalendarEvents;

abstract class Calendar
{
    protected TheInternet $conn;

    public function __construct(public readonly string $email) {}

    public function connect(TheInternet $internet): void
    {
        $this->conn = $internet;
    }

    public abstract function newMessage(Calendar $sender, InviteReceived|CancelEvent|MoveEvent|AddGuest $message): string;

    public abstract function revokeMessage(string $id);

    /**
     * @return string Unique string key
     */
    abstract public function newEvent(AddEvent $e): string;

    abstract public function modifyEvent(AddGuest|CancelEvent|MoveEvent $change): void;

    /**
     * @return iterable<InviteReceived>
     */
    abstract public function inbox(): iterable;

    protected static function RANDOM_STRING($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    abstract public function listEvents(string $string);
}
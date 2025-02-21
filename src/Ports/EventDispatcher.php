<?php
namespace App\Ports;

interface EventDispatcher
{
    public function dispatch(object $event): void;
    public function addListener(string $eventName, callable $listener): void;
}

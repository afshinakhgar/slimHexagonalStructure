<?php
namespace App\Infrastructure;

use App\Ports\EventDispatcher;

class SimpleEventDispatcher implements EventDispatcher
{
    private array $listeners = [];

    public function dispatch(object $event): void
    {
        $eventName = get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                $listener($event);
            }
        }
    }

    public function addListener(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }
}

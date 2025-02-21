<?php
namespace App\Infrastructure\Listeners;

use App\Domain\UserRegisteredEvent;

class LogUserRegistration
{
    public function __invoke(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        echo "User registered: {$user->getEmail()}\n";
    }
}

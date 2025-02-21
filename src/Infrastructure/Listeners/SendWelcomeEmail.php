<?php
namespace App\Infrastructure\Listeners;

use App\Domain\Events\UserRegisteredEvent;

class SendWelcomeEmail
{
    public function __invoke(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        echo "Sending welcome email to: {$user->getEmail()}\n";
    }
}

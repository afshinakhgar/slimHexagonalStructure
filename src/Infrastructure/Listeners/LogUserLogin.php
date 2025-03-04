<?php
namespace App\Infrastructure\Listeners;

use App\Domain\Events\UserLoggedInEvent;

class LogUserLogin
{
    public function __invoke(UserLoggedInEvent $event): void
    {
        $user = $event->getUser();
        echo "User logged in: {$user->getEmail()}\n";
    }
}

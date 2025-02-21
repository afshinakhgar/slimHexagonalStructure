<?php
namespace App\Domain\Events;

use App\Domain\Event;

class UserRegisteredEvent extends Event
{
    private \App\Domain\Entity\User $user;

    public function __construct(\App\Domain\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getUser(): \App\Domain\Entity\User
    {
        return $this->user;
    }
}

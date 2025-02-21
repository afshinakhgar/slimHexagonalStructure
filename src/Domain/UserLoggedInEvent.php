<?php
namespace App\Domain;

use App\Domain\Event;

class UserLoggedInEvent extends Event
{
    private \App\Domain\User $user;

    public function __construct(\App\Domain\User $user)
    {
        $this->user = $user;
    }

    public function getUser(): \App\Domain\User
    {
        return $this->user;
    }
}

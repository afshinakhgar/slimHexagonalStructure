<?php
namespace App\Ports;

interface UserRepository
{
    public function save(\App\Domain\User $user): void;
    public function findById(int $id): ?\App\Domain\User;
    public function findByEmail(string $email): ?\App\Domain\User;
}

<?php
namespace App\Ports;

interface UserRepository
{
    public function save(\App\Domain\Entity\User $user): void;
    public function findById(int $id): ?\App\Domain\Entity\User;
    public function findByEmail(string $email): ?\App\Domain\Entity\User;
}

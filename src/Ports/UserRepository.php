<?php
namespace App\Ports;
use \App\Domain\Entity\User;
interface UserRepository
{
    public function save(User $user): void;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ? User;
}

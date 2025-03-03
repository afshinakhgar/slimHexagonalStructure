<?php
namespace App\Application\Services\User;

use App\Ports\Repository\UserRepository;

class CreateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $name, string $email): \App\Domain\Entity\User
    {
        $id = random_int(1, 1000);
        $user = new \App\Domain\Entity\User($id, $name, $email, '');
        $this->userRepository->save($user);
        return $user;
    }
}

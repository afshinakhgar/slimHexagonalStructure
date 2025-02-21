<?php
namespace App\Application;

use App\Ports\UserRepository;
use App\Ports\EventDispatcher;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;
    private string $jwtSecret;

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher, string $jwtSecret)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->jwtSecret = $jwtSecret;
    }

    public function register(string $name, string $email, string $password): \App\Domain\User
    {
        $id = random_int(1, 1000);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new \App\Domain\User($id, $name, $email, $hashedPassword);
        $this->userRepository->save($user);
        $this->eventDispatcher->dispatch(new \App\Domain\UserRegisteredEvent($user));
        return $user;
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->getPassword())) {
            return $this->generateToken($user);
        }
        return null;
    }

    private function generateToken(\App\Domain\User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'iat' => time(),
            'exp' => time() + 3600
        ];
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}

<?php
namespace App\Application\Services;

use App\Ports\EventDispatcher;
use App\Ports\UserRepository;
use Firebase\JWT\JWT;
use Monolog\Logger;

class AuthService
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;
    private string $jwtSecret;
    private Logger $logger; // Define the logger property

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher, string $jwtSecret, Logger $logger)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->jwtSecret = $jwtSecret;
    }

    public function register(string $name, string $email, string $password): \App\Domain\Entity\User
    {
        $id = rand(1, 1000);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new \App\Domain\Entity\User($id, $name, $email, $hashedPassword);
        $this->userRepository->save($user);
        $this->eventDispatcher->dispatch(new \App\Domain\Events\UserRegisteredEvent($user));
        return $user;
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->getPassword())) {
            return $this->generateToken($user);
        }

        $this->logger->warning("Failed login attempt for email: {$email}");

        return null;
    }

    private function generateToken(\App\Domain\Entity\User $user): string
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

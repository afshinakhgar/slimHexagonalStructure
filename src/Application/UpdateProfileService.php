<?php
namespace App\Application;

use App\Ports\UserRepository;
use App\Ports\EventDispatcher;

class UpdateProfileService
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute(int $userId, string $name): \App\Domain\User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $updatedUser = new \App\Domain\User($user->getId(), $name, $user->getEmail(), $user->getPassword());
        $this->userRepository->save($updatedUser);
        $this->eventDispatcher->dispatch(new \App\Domain\UserProfileUpdatedEvent($updatedUser));
        return $updatedUser;
    }
}

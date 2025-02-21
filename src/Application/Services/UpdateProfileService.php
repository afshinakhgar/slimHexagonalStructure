<?php
namespace App\Application\Services;

use App\Ports\EventDispatcher;
use App\Ports\UserRepository;

class UpdateProfileService
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute(int $userId, string $name): \App\Domain\Entity\User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $updatedUser = new \App\Domain\Entity\User($user->getId(), $name, $user->getEmail(), $user->getPassword());
        $this->userRepository->save($updatedUser);
        $this->eventDispatcher->dispatch(new \App\Domain\Events\UserProfileUpdatedEvent($updatedUser));
        return $updatedUser;
    }
}

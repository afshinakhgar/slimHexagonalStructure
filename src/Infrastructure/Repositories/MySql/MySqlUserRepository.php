<?php
namespace App\Infrastructure\Repositories\MySql;

use App\Domain\Entity\User;
use App\Ports\UserRepository;
use PDO;

class MySqlUserRepository implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (id, name, email, password) VALUES (:id, :name, :email, :password)
             ON DUPLICATE KEY UPDATE name = :name, email = :email, password = :password"
        );
        $stmt->execute([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['name'], $data['email'], $data['password']) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['name'], $data['email'], $data['password']) : null;
    }
}
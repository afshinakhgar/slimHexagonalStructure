<?php
namespace Tests\Unit;

use App\Application\Services\CreateUserService;
use App\Domain\Entity\User;
use App\Ports\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateUserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecuteCreatesAndSavesAUser()
    {
        // Arrange: ماک Repository
        $mockRepository = Mockery::mock(UserRepository::class);

        // مشخص کردن رفتار ماک
        $mockRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function ($user) {
                return $user instanceof User &&
                    $user->getName() === 'John Doe' &&
                    $user->getEmail() === 'john@example.com';
            }));

        // Act: ایجاد شیء CreateUserService و فراخوانی متد execute
        $service = new CreateUserService($mockRepository);
        $user = $service->execute('John Doe', 'john@example.com');

        // Assert: بررسی نتیجه
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
    }
}
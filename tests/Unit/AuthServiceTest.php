<?php
namespace Tests\Unit;

use App\Application\Services\AuthService;
use App\Domain\Entity\User;
use App\Domain\Events\UserRegisteredEvent;
use App\Ports\EventDispatcher;
use App\Ports\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testRegisterCreatesAndSavesAUser()
    {
        // Arrange: ماک Repository و EventDispatcher
        $mockRepository = Mockery::mock(UserRepository::class);
        $mockDispatcher = Mockery::mock(EventDispatcher::class);

        // مشخص کردن رفتار ماک‌ها
        $mockRepository->shouldReceive('save')->once()->with(Mockery::type(User::class));
        $mockDispatcher->shouldReceive('dispatch')->once()->with(Mockery::on(function ($event) {
            return $event instanceof UserRegisteredEvent &&
                $event->getUser()->getName() === 'John Doe' &&
                $event->getUser()->getEmail() === 'john@example.com';
        }));

        // Act: ایجاد شیء AuthService و فراخوانی متد register
        $service = new AuthService($mockRepository, $mockDispatcher, 'your-secret-key');
        $user = $service->register('John Doe', 'john@example.com', 'secret');

        // Assert: بررسی نتیجه
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertTrue(password_verify('secret', $user->getPassword()));
    }
}
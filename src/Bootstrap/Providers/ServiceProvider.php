<?php
namespace App\Bootstrap\Providers;

use App\Application\Services\{User\AuthService, User\CreateUserService, User\UpdateProfileService};
use App\Infrastructure\Listeners\{LogUserRegistration, SendWelcomeEmail};
use App\Infrastructure\SimpleEventDispatcher;
use App\Ports\EventDispatcher;
use DI\ContainerBuilder;

class ServiceProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            CreateUserService::class => \DI\autowire(),
            AuthService::class => \DI\autowire()->constructorParameter('jwtSecret', 'your-secret-key'),
            UpdateProfileService::class => \DI\autowire(),
            EventDispatcher::class => function ($container) {
                $dispatcher = new SimpleEventDispatcher();
                $dispatcher->addListener(
                    \App\Domain\Events\UserRegisteredEvent::class,
                    $container->get(LogUserRegistration::class)
                );
                $dispatcher->addListener(
                    \App\Domain\Events\UserRegisteredEvent::class,
                    $container->get(SendWelcomeEmail::class)
                );
                return $dispatcher;
            },
            LogUserRegistration::class => \DI\autowire(),
            SendWelcomeEmail::class => \DI\autowire(),
        ]);
    }
}
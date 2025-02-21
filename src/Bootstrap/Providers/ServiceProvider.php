<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use App\Application\Services\{CreateUserService, AuthService, UpdateProfileService};
use App\Ports\EventDispatcher;
use App\Infrastructure\SimpleEventDispatcher;
use App\Infrastructure\Listeners\{LogUserRegistration, SendWelcomeEmail, LogUserLogin};

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
                    \App\Domain\UserRegisteredEvent::class,
                    $container->get(LogUserRegistration::class)
                );
                $dispatcher->addListener(
                    \App\Domain\UserRegisteredEvent::class,
                    $container->get(SendWelcomeEmail::class)
                );
                $dispatcher->addListener(
                    \App\Domain\UserLoggedInEvent::class,
                    $container->get(LogUserLogin::class)
                );
                return $dispatcher;
            },
            LogUserRegistration::class => \DI\autowire(),
            SendWelcomeEmail::class => \DI\autowire(),
            LogUserLogin::class => \DI\autowire(),
        ]);
    }
}

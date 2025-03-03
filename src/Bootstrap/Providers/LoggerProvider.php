<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            // تعریف Logger اصلی
            Logger::class => function () {
                $logger = new Logger('app_logger');
                $logger->pushHandler(new StreamHandler(
                    __DIR__ . '/../../../../storage/logs/app.log',
                    Logger::DEBUG
                ));
                return $logger;
            },

            // تعریف فانکشن لاگینگ
            'logger.function' => function (Logger $logger) {
                return function (string $message, string $level = 'info', array $context = []) use ($logger) {
                    switch (strtolower($level)) {
                        case 'debug':
                            $logger->debug($message, $context);
                            break;
                        case 'info':
                            $logger->info($message, $context);
                            break;
                        case 'warning':
                            $logger->warning($message, $context);
                            break;
                        case 'error':
                            $logger->error($message, $context);
                            break;
                        default:
                            $logger->info($message, $context);
                    }
                };
            }
        ]);
    }
}
<?php

/**
 * Check if a string is a valid email address.
 *
 * @param string $email
 * @return bool
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate a random string of a given length.
 *
 * @param int $length
 * @return string
 */
function generateRandomString(int $length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Format a date to a human-readable format.
 *
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string
{
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
    return $dateTime ? $dateTime->format($format) : 'Invalid Date';
}

/**
 * Dump and die (debugging helper).
 *
 * @param mixed ...$data
 */
/**
 * Dump and die (debugging helper).
 *
 * @param mixed ...$data
 */
function dd(...$data): void
{
    echo '<pre>';
    foreach ($data as $item) {
        var_dump($item);
    }
    echo '</pre>';
    die();
}


use App\Infrastructure\ConfigLoader;

if (!function_exists('config')) {
    $configLoader = null;

    function config(string $key, $default = null)
    {
        global $configLoader;

        if ($configLoader === null) {
            $configLoader = new ConfigLoader(__DIR__ . '/../config');
        }

        return $configLoader->get($key, $default);
    }
}
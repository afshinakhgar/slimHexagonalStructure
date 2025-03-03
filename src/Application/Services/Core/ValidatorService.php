<?php
namespace App\Application\Services\Core;

use Respect\Validation\Validator as v;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpMethodNotAllowedException;

class ValidatorService
{

    public static function validateIp(ServerRequestInterface $request): void
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? null;
        if (!v::ip()->validate($ip)) {
            throw new HttpBadRequestException($request, "Invalid IP address");
        }
    }

    /**
     * بررسی می‌کند که آیا متد درخواست مجاز است یا خیر
     */
    public static function validateMethod(ServerRequestInterface $request): void
    {
        if (!v::in(['GET', 'POST'])->validate($request->getMethod())) {
            throw new HttpMethodNotAllowedException($request, "Method not allowed");
        }
    }


    public static function validateJson(ServerRequestInterface $request): array
    {
        $body = $request->getBody()->getContents();
        if (!empty($body) && !v::json()->validate($body)) {
            throw new HttpBadRequestException($request, "Invalid JSON format");
        }

        return json_decode($body, true) ?? [];
    }


    public static function validateRequiredKeys(array $data, array $requiredKeys): void
    {
        foreach ($requiredKeys as $key => $validator) {
            if (!isset($data[$key]) || !$validator->validate($data[$key])) {
                throw new HttpBadRequestException(null, "Missing or invalid field: $key");
            }
        }
    }

    /**
     * بررسی می‌کند که آیا مقدار ایمیل معتبر است یا خیر
     */
    public static function validateEmail(?string $email): void
    {
        if ($email !== null && !v::email()->validate($email)) {
            throw new HttpBadRequestException(null, "Invalid email address");
        }
    }


    public static function validatePhone(?string $phone): void
    {
        if ($phone !== null && !v::digit()->length(10, 15)->validate($phone)) {
            throw new HttpBadRequestException(null, "Invalid phone number");
        }
    }
}

<?php
namespace App\Application\Http\Controllers\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Services\UpdateProfileService;

class UpdateProfile
{
    private UpdateProfileService $updateProfileService;

    public function __construct(UpdateProfileService $updateProfileService)
    {
        $this->updateProfileService = $updateProfileService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $userId = $request->getAttribute('user_id');
        $user = $this->updateProfileService->execute($userId, $data['name']);
        $response->getBody()->write(json_encode(['id' => $user->getId(), 'name' => $user->getName()]));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    }
}
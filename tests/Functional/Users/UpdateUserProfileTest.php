<?php
namespace Tests\Functional\Users;

use App\Bootstrap\AppSetup;
use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream;

class UpdateUserProfileTest extends TestCase
{
    public function testUpdateProfile()
    {
        // Get the Slim app instance
        $app = AppSetup::getApp();

        // Create a valid Uri object
        $uri = new Uri('http', 'localhost', 80, '/login');

        // Create a valid Headers object
        $headers = new Headers();
        $headers->addHeader('Content-Type', 'application/json');

        // Create a valid Stream object for the body
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode(['name' => 'Jane Doe','email'=>'john@doe.com','password'=>'password']));
        $body->seek(0); // Reset stream pointer to the beginning

        // Create the Request object with all required parameters
        $request = new Request(
            'POST', // method
            $uri,  // uri
            $headers, // headers
            [],    // cookieParams
            [],    // serverParams
            $body, // body
            []     // uploadedFiles
        );

        // Add user_id attribute to simulate authenticated user
        $request = $request->withAttribute('user_id', 1);

        // Handle the request
        $response = $app->handle($request);
        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson((string)$response->getBody());
        $responseData = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals('Jane Doe', $responseData['name']);
    }
}
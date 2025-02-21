<?php
namespace Tests\Functional\Users;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream;
use App\Bootstrap\AppSetup;

class RegisterUserTest extends TestCase
{
    public function testRegister()
    {
        // Get the Slim app instance
        $app = AppSetup::getApp();

        // Create a valid Uri object
        $uri = new Uri('http', 'localhost', 80, '/register');

        // Create a valid Headers object
        $headers = new Headers();
        $headers->addHeader('Content-Type', 'application/json');

        // Create a valid Stream object for the body
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'secret']));
        $body->seek(0); // Reset stream pointer to the beginning

        // Create the Request object with all required parameters
        $request = new Request(
            'POST', // method
            $uri,   // uri
            $headers, // headers
            [],     // cookieParams
            [],     // serverParams
            $body,  // body
            []      // uploadedFiles
        );

        // Handle the request
        $response = $app->handle($request);

        // Assertions
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson((string)$response->getBody());
        $responseData = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertEquals('john@example.com', $responseData['email']);
    }
}
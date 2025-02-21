<?php
namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream; // Import Stream class

class AuthControllerTest extends TestCase
{
    public function testRegister()
    {
        $app = AppFactory::create();
        $routes = require __DIR__ . '/../../src/Routes/web.php';
        $routes($app);

        // Create a valid Uri object
        $uri = new Uri('http', 'localhost', 80, '/register');

        // Create a valid Headers object
        $headers = new Headers();

        // Create a valid Stream object for the body
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'secret']));

        // Use seek(0) instead of rewind()
        $body->seek(0); // Reset stream pointer to the beginning

        // Create the Request object with all required parameters
        $request = new Request(
            'POST', // method
            $uri,   // uri
            $headers, // headers
            [],     // serverParams
            [],     // uploadedFiles
            [],     // cookieParams
            [],     // queryParams
            $body   // parsedBody (as Stream)
        );

        // Handle the request
        $response = $app->handle($request);

        // Assertions
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson((string)$response->getBody());
    }
}
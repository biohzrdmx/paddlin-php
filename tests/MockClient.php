<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use RuntimeException;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Caldera\Http\Response;

class MockClient implements ClientInterface {

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface {
        $uri = $request->getUri();
        $fixture = sprintf('%s/fixtures/responses/%s%s.json', dirname(__FILE__), strtolower($request->getMethod()), str_replace('/', '_', $uri->getPath()));
        if ( file_exists($fixture) ) {
            $contents = file_get_contents($fixture);
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $response = new Response(200, $headers, $contents);
        } else {
            throw new RuntimeException("Fixture not found: {$fixture}");
        }
        return $response;
    }
}

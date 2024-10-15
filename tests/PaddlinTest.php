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

use PHPUnit\Framework\TestCase;

use Caldera\Http\Factory;

use Paddlin\Client;

abstract class PaddlinTest extends TestCase {

    /**
     * Get the payload for an specific request
     * @param  string $method   HTTP method
     * @param  string $endpoint Endpoint URL
     */
    protected function getRequestPayload(string $method, string $endpoint): array {
        $fixture = sprintf('%s/fixtures/requests/%s%s.json', dirname(__FILE__), $method, str_replace('/', '_', $endpoint));
        if ( file_exists($fixture) ) {
            $contents = file_get_contents($fixture);
            return json_decode($contents, true);
        } else {
            throw new RuntimeException("Fixture not found: {$fixture}");
        }
    }

    /**
     * Get Client instance
     */
    protected function getClient(): Client {
        $http_client = new MockClient();
        $request_factory = new Factory();
        return new Client($http_client, $request_factory, 'API_KEY_PLACEHOLDER', true);
    }
}

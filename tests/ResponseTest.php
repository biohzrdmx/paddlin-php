<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Exception;
use RuntimeException;

use PHPUnit\Framework\TestCase;

use Caldera\Http\Response as HttpResponse;

use Paddlin\ApiException;
use Paddlin\Response;

class ResponseTest extends TestCase {

    public function testInvalidResponse() {
        $this->expectException(RuntimeException::class);
        $response = new Response(new HttpResponse(500));
    }

    public function testErrorResponse() {
        try {
            $response = new Response(new HttpResponse(400, body: '{"error":{"type":"request_error","code":"not_found","detail":"Entity pro_01gsz97mq9pa4fkyy0wqenepkz not found","documentation_url":"https://developer.paddle.com/errors/shared/not_found"},"meta":{"request_id":"9346b365-4cad-43a6-b7c1-48ff6a1c7836"}}'));
            $this->fail('Exception not thrown');
        } catch (ApiException $e) {
            $error = $e->getError();
            $this->assertEquals('request_error', $error['type']);
        } catch (Exception $e) {
            $this->fail('Unexpected Exception ocurred');
        }
    }

    public function testResponse() {
        $fixture = file_get_contents(dirname(__FILE__) . '/fixtures/responses/get_products.json');
        $response = new Response(new HttpResponse(200, body: $fixture));
        $this->assertTrue($response->hasMeta());
        $this->assertArrayHasKey('request_id', $response->getMeta());
        $this->assertTrue($response->hasPagination());
    }
}

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
use Caldera\Http\Stream;

use Paddlin\Webhook\Notification;
use Paddlin\Webhook\Webhook;

class WebhookTest extends TestCase {

    public function testWebhookWrongSignature() {
        $fixture = file_get_contents(dirname(__FILE__) . '/fixtures/webhook/subscription_created.json');
        $factory = new Factory();
        $stream = new Stream($fixture);
        $request = $factory->createServerRequest('POST', 'http://34.194.127.46');
        $request = $request->withAddedHeader('Paddle-Signature', 'ts=1671552777;h1=a65569c4b8b56659a0137c0d388257498baa1ab6b036da9bd1462d540b664016');
        $request = $request->withBody($stream);
        $webhook = new Webhook($request, 'pdl_ntfset_01gkpjp8bkm3tm53kdgkx6sms7_a7e4a56cf7c1cd80cb1c735c72bab2aa75d06fe08b8c50a18cf6afbcfe834122');
        $this->assertFalse( $webhook->verify() );
    }

    public function testWebhookNotification() {
        $fixture = file_get_contents(dirname(__FILE__) . '/fixtures/webhook/subscription_created.json');
        $factory = new Factory();
        $stream = new Stream($fixture);
        $request = $factory->createServerRequest('POST', 'http://34.194.127.46');
        $request = $request->withAddedHeader('Paddle-Signature', 'ts=1671552777;h1=1c6ab9ed5b323b3d0c132770b7613f8cc6dd9102bf6a34d77277f907ade3e00c');
        $request = $request->withBody($stream);
        $webhook = new Webhook($request, 'pdl_ntfset_01gkpjp8bkm3tm53kdgkx6sms7_a7e4a56cf7c1cd80cb1c735c72bab2aa75d06fe08b8c50a18cf6afbcfe834122');
        $this->assertTrue( $webhook->verify() );
        $notification = $webhook->getNotification();
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals('subscription.created', $notification->event_type);
        $this->assertEquals('evt_01hv8x2acma2gz7he8kg2s0hna', $notification->event_id);
    }

    public function testWebhookInvalidNotification() {
        $fixture = file_get_contents(dirname(__FILE__) . '/fixtures/webhook/invalid_notification.json');
        $factory = new Factory();
        $stream = new Stream($fixture);
        $request = $factory->createServerRequest('POST', 'http://34.194.127.46');
        $request = $request->withAddedHeader('Paddle-Signature', 'ts=1671552777;h1=1c6ab9ed5b323b3d0c132770b7613f8cc6dd9102bf6a34d77277f907ade3e00c');
        $request = $request->withBody($stream);
        $webhook = new Webhook($request, 'pdl_ntfset_01gkpjp8bkm3tm53kdgkx6sms7_a7e4a56cf7c1cd80cb1c735c72bab2aa75d06fe08b8c50a18cf6afbcfe834122');
        $this->expectException(RuntimeException::class);
        $notification = $webhook->getNotification();
    }
}

<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\SubscriptionCollection;
use Paddlin\Entities\Subscription;
use Paddlin\Entities\Transaction;

class SubscriptionsTest extends PaddlinTest {

    public function testSubscriptionsList() {
        $client = $this->getClient();
        #
        $subscriptions = $client->subscriptions->list();
        $this->assertInstanceOf(SubscriptionCollection::class, $subscriptions);
        $this->assertCount(4, $subscriptions);
        $this->assertFalse($subscriptions->hasMore());
        #
        $subscriptions = $subscriptions->next();
        $this->assertInstanceOf(SubscriptionCollection::class, $subscriptions);
        $this->assertCount(4, $subscriptions);
    }

    public function testSubscriptionsGet() {
        $client = $this->getClient();
        #
        $subscription = $client->subscriptions->get('sub_01hp463gxfvndqjjyqn2n7tkth');
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->status);
    }

    public function testSubscriptionsUpdatePreview() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions');
        $subscription = $client->subscriptions->previewUpdate($payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('GBP', $subscription->currency_code);
    }

    public function testSubscriptionsUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions');
        $subscription = $client->subscriptions->update('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('GBP', $subscription->currency_code);
    }

    public function testSubscriptionsPaymentMethodChangeTransaction() {
        $client = $this->getClient();
        #
        $transaction = $client->subscriptions->getPaymentMethodChangeTransaction('sub_01hp463gxfvndqjjyqn2n7tkth');
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('subscription_payment_method_change', $transaction->origin);
    }

    public function testSubscriptionsPreviewOneTimeCharge() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions/charge/preview');
        $subscription = $client->subscriptions->createOneTimeCharge('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->status);
    }

    public function testSubscriptionsCreateOneTimeCharge() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions/charge');
        $subscription = $client->subscriptions->previewOneTimeCharge('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->status);
    }

    public function testSubscriptionsActivate() {
        $client = $this->getClient();
        #
        $subscription = $client->subscriptions->activate('sub_01hp463gxfvndqjjyqn2n7tkth');
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->status);
    }

    public function testSubscriptionsPause() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions/pause');
        $subscription = $client->subscriptions->pause('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('paused', $subscription->status);
    }

    public function testSubscriptionsCancel() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions/cancel');
        $subscription = $client->subscriptions->cancel('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('cancelled', $subscription->status);
    }

    public function testSubscriptionsResume() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/subscriptions/resume');
        $subscription = $client->subscriptions->resume('sub_01hp463gxfvndqjjyqn2n7tkth', $payload);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->status);
    }
}

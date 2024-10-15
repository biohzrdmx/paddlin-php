<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\DiscountCollection;
use Paddlin\Entities\Discount;

class DiscountsTest extends PaddlinTest {

    public function testDiscountsList() {
        $client = $this->getClient();
        #
        $discounts = $client->discounts->list();
        $this->assertInstanceOf(DiscountCollection::class, $discounts);
        $this->assertCount(4, $discounts);
        $this->assertFalse($discounts->hasMore());
        #
        $discounts = $discounts->next();
        $this->assertInstanceOf(DiscountCollection::class, $discounts);
        $this->assertCount(4, $discounts);
    }

    public function testDiscountsGet() {
        $client = $this->getClient();
        #
        $discount = $client->discounts->get('dsc_01h83xenpcfjyhkqr4x214m02x');
        $this->assertInstanceOf(Discount::class, $discount);
        #
        $this->assertEquals("dsc_01h83xenpcfjyhkqr4x214m02x", $discount->id);
        $this->assertEquals("active", $discount->status);
        $this->assertEquals("Nonprofit discount", $discount->description);
        $this->assertEquals(true, $discount->enabled_for_checkout);
        $this->assertEquals("ABCDE12345", $discount->code);
        $this->assertEquals("percentage", $discount->type);
        $this->assertEquals("10", $discount->amount);
        $this->assertEquals("USD", $discount->currency_code);
        $this->assertEquals(true, $discount->recur);
        $this->assertEquals(5, $discount->maximum_recurring_intervals);
        $this->assertEquals(1000, $discount->usage_limit);
        $this->assertEquals(['pro_01gsz4t5hdjse780zja8vvr7jg', 'pro_01gsz4s0w61y0pp88528f1wvvb'], $discount->restrict_to);
        $this->assertEquals(0, $discount->times_used);
    }

    public function testDiscountsCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/discounts');
        $discount = $client->discounts->create($payload);
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals('ABCDE12345', $discount->code);
    }

    public function testDiscountsUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/discounts');
        $discount = $client->discounts->update('dsc_01h83xenpcfjyhkqr4x214m02x', $payload);
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals('20', $discount->amount);
    }
}

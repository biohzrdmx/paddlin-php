<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\PriceCollection;
use Paddlin\Entities\Price;

class PricesTest extends PaddlinTest {

    public function testPricesList() {
        $client = $this->getClient();
        #
        $prices = $client->prices->list();
        $this->assertInstanceOf(PriceCollection::class, $prices);
        $this->assertCount(16, $prices);
        $this->assertFalse($prices->hasMore());
        #
        $prices = $prices->next();
        $this->assertInstanceOf(PriceCollection::class, $prices);
        $this->assertCount(16, $prices);
    }

    public function testPricesGet() {
        $client = $this->getClient();
        #
        $price = $client->prices->get('pri_01he6hp8cg49jjf1pdjf6d5yw1');
        $this->assertInstanceOf(Price::class, $price);
        #
        $this->assertEquals('Weekly', $price->name);
        $this->assertEquals('pro_01gsz4t5hdjse780zja8vvr7jg', $price->product_id);
        $this->assertEquals('Weekly (per seat)', $price->description);
        $this->assertEquals('', $price->type);
        $this->assertEquals('account_setting', $price->tax_mode);
    }

    public function testPricesCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/prices');
        $price = $client->prices->create($payload);
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('Weekly', $price->name);
    }

    public function testPricesUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/prices');
        $price = $client->prices->update('pri_01he6hp8cg49jjf1pdjf6d5yw1', $payload);
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('Annually', $price->name);
    }
}

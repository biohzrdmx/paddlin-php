<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\AddressCollection;
use Paddlin\Entities\Address;

class AddressesTest extends PaddlinTest {

    public function testAddressesList() {
        $client = $this->getClient();
        #
        $addresses = $client->addresses->list('ctm_01h844p3h41s12zs5mn4axja51');
        $this->assertInstanceOf(AddressCollection::class, $addresses);
        $this->assertCount(2, $addresses);
        $this->assertFalse($addresses->hasMore());
        #
        $addresses = $addresses->next();
        $this->assertInstanceOf(AddressCollection::class, $addresses);
        $this->assertCount(2, $addresses);
    }

    public function testAddressesGet() {
        $client = $this->getClient();
        #
        $address = $client->addresses->get('ctm_01h844p3h41s12zs5mn4axja51', 'add_01h848pep46enq8y372x7maj0p');
        $this->assertInstanceOf(Address::class, $address);
        #
        $this->assertEquals("add_01h848pep46enq8y372x7maj0p", $address->id);
        $this->assertEquals("ctm_01h844p3h41s12zs5mn4axja51", $address->customer_id);
        $this->assertEquals("active", $address->status);
        $this->assertEquals("Head Office", $address->description);
        $this->assertEquals("4050 Jefferson Plaza, 41st Floor", $address->first_line);
        $this->assertEquals(null, $address->second_line);
        $this->assertEquals("New York", $address->city);
        $this->assertEquals("10021", $address->postal_code);
        $this->assertEquals("NY", $address->region);
        $this->assertEquals("US", $address->country_code);
    }

    public function testAddressesCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/addresses');
        $address = $client->addresses->create('ctm_01h844p3h41s12zs5mn4axja51', $payload);
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Head Office', $address->description);
    }

    public function testAddressesUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/addresses');
        $address = $client->addresses->update('ctm_01h844p3h41s12zs5mn4axja51', 'add_01h848pep46enq8y372x7maj0p', $payload);
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Main Office', $address->description);
    }
}

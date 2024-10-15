<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\BusinessCollection;
use Paddlin\Entities\Business;

class BusinessesTest extends PaddlinTest {

    public function testBusinessesList() {
        $client = $this->getClient();
        #
        $businesses = $client->businesses->list('ctm_01h844p3h41s12zs5mn4axja51');
        $this->assertInstanceOf(BusinessCollection::class, $businesses);
        $this->assertCount(1, $businesses);
        $this->assertFalse($businesses->hasMore());
        #
        $businesses = $businesses->next();
        $this->assertInstanceOf(BusinessCollection::class, $businesses);
        $this->assertCount(1, $businesses);
    }

    public function testBusinessesGet() {
        $client = $this->getClient();
        #
        $business = $client->businesses->get('ctm_01h844p3h41s12zs5mn4axja51', 'biz_01h84a7hr4pzhsajkm8tev89ev');
        $this->assertInstanceOf(Business::class, $business);
        #
        $this->assertEquals("biz_01h84a7hr4pzhsajkm8tev89ev", $business->id);
        $this->assertEquals("ctm_01h844p3h41s12zs5mn4axja51", $business->customer_id);
        $this->assertEquals("active", $business->status);
        $this->assertEquals("ChatApp Inc.", $business->name);
        $this->assertEquals("555775291485", $business->company_number);
        $this->assertEquals("555952383", $business->tax_identifier);
    }

    public function testBusinessesCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/businesses');
        $business = $client->businesses->create('ctm_01h844p3h41s12zs5mn4axja51', $payload);
        $this->assertInstanceOf(Business::class, $business);
        $this->assertEquals('ChatApp Inc.', $business->name);
    }

    public function testBusinessesUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/businesses');
        $business = $client->businesses->update('ctm_01h844p3h41s12zs5mn4axja51', 'biz_01h84a7hr4pzhsajkm8tev89ev', $payload);
        $this->assertInstanceOf(Business::class, $business);
        $this->assertEquals('ChatApp Ltd.', $business->name);
    }
}

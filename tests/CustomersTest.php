<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\CreditBalanceCollection;
use Paddlin\Collections\CustomerCollection;
use Paddlin\Entities\Customer;

class CustomersTest extends PaddlinTest {

    public function testCustomersList() {
        $client = $this->getClient();
        #
        $customers = $client->customers->list();
        $this->assertInstanceOf(CustomerCollection::class, $customers);
        $this->assertCount(3, $customers);
        $this->assertFalse($customers->hasMore());
        #
        $customers = $customers->next();
        $this->assertInstanceOf(CustomerCollection::class, $customers);
        $this->assertCount(3, $customers);
    }

    public function testCustomersGet() {
        $client = $this->getClient();
        #
        $customer = $client->customers->get('ctm_01h844p3h41s12zs5mn4axja51');
        $this->assertInstanceOf(Customer::class, $customer);
        #
        $this->assertEquals('active', $customer->status);
        $this->assertEquals('Alex Wilson', $customer->name);
        $this->assertEquals('test2@example.com', $customer->email);
        $this->assertEquals(false, $customer->marketing_consent);
        $this->assertEquals('en', $customer->locale);
    }

    public function testCustomersCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/customers');
        $customer = $client->customers->create($payload);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Alex Wilson', $customer->name);
    }

    public function testCustomersUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/customers');
        $customer = $client->customers->update('ctm_01h844p3h41s12zs5mn4axja51', $payload);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Wade Wilson', $customer->name);
    }

    public function testCustomerCreditBalances() {
        $client = $this->getClient();
        #
        $credit_balances = $client->customers->creditBalances('ctm_01h844p3h41s12zs5mn4axja51');
        $this->assertInstanceOf(CreditBalanceCollection::class, $credit_balances);
        $this->assertCount(1, $credit_balances);
        $this->assertFalse($credit_balances->hasMore());
    }
}

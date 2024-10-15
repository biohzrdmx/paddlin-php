<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\CreditBalanceCollection;
use Paddlin\Collections\CustomerCollection;
use Paddlin\Entities\CreditBalance;
use Paddlin\Entities\Customer;

class Customers extends AbstractResource {

    /**
     * List customers
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): CustomerCollection {
        $response = $this->client->getRequest('/customers', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Customer($properties);
            }
        }
        # Build collection
        $collection = new CustomerCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get customer
     * @param  string $customer_id  Customer ID
     * @param  array  $parameters   Query parameters
     */
    public function get(string $customer_id, array $parameters = []): ?Customer {
        $response = $this->client->getRequest("/customers/{$customer_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Customer( $response->getData() );
        }
        return null;
    }

    /**
     * Create customer
     * @param  array  $payload  Request payload
     */
    public function create(array $payload = []): ?Customer {
        $response = $this->client->postRequest('/customers', $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Customer( $response->getData() );
        }
        return null;
    }

    /**
     * Update customer
     * @param  string $customer_id  Customer ID
     * @param  array  $payload      Request payload
     */
    public function update(string $customer_id, array $payload = []): ?Customer {
        $response = $this->client->patchRequest("/customers/{$customer_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Customer( $response->getData() );
        }
        return null;
    }

    /**
     * Get credit balances
     * @param  string $customer_id  Customer ID
     */
    public function creditBalances(string $customer_id): CreditBalanceCollection {
        $response = $this->client->getRequest("/customers/{$customer_id}/credit-balances");
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new CreditBalance($properties);
            }
        }
        # Build collection
        $collection = new CreditBalanceCollection($this, $items, $response->getPagination());
        return $collection;
    }
}

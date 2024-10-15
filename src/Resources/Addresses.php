<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\AddressCollection;
use Paddlin\Entities\Address;

class Addresses extends AbstractResource {

    /**
     * List addresses
     * @param  string $customer_id Customer ID
     * @param  array  $parameters  Query parameters
     */
    public function list(string $customer_id, array $parameters = []): AddressCollection {
        $response = $this->client->getRequest("/customers/{$customer_id}/addresses", $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Address($properties);
            }
        }
        # Build collection
        $collection = new AddressCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get address
     * @param  string $customer_id Customer ID
     * @param  string $address_id  Address ID
     * @param  array  $parameters  Query parameters
     */
    public function get(string $customer_id, string $address_id, array $parameters = []): ?Address {
        $response = $this->client->getRequest("/customers/{$customer_id}/addresses/{$address_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Address( $response->getData() );
        }
        return null;
    }

    /**
     * Create address
     * @param  string $customer_id Customer ID
     * @param  array  $payload     Request payload
     */
    public function create(string $customer_id, array $payload = []): ?Address {
        $response = $this->client->postRequest("/customers/{$customer_id}/addresses", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Address( $response->getData() );
        }
        return null;
    }

    /**
     * Update address
     * @param  string $customer_id Customer ID
     * @param  string $address_id  Address ID
     * @param  array  $payload     Request payload
     */
    public function update(string $customer_id, string $address_id, array $payload = []): ?Address {
        $response = $this->client->patchRequest("/customers/{$customer_id}/addresses/{$address_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Address( $response->getData() );
        }
        return null;
    }
}

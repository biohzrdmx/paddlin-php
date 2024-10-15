<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\BusinessCollection;
use Paddlin\Entities\Business;

class Businesses extends AbstractResource {

    /**
     * List businesses
     * @param  string $customer_id Customer ID
     * @param  array  $parameters  Query parameters
     */
    public function list(string $customer_id, array $parameters = []): BusinessCollection {
        $response = $this->client->getRequest("/customers/{$customer_id}/businesses", $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Business($properties);
            }
        }
        # Build collection
        $collection = new BusinessCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get business
     * @param  string $customer_id Customer ID
     * @param  string $business_id Business ID
     * @param  array  $parameters  Query parameters
     */
    public function get(string $customer_id, string $business_id, array $parameters = []): ?Business {
        $response = $this->client->getRequest("/customers/{$customer_id}/businesses/{$business_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Business( $response->getData() );
        }
        return null;
    }

    /**
     * Create business
     * @param  string $customer_id Customer ID
     * @param  array  $payload     Request payload
     */
    public function create(string $customer_id, array $payload = []): ?Business {
        $response = $this->client->postRequest("/customers/{$customer_id}/businesses", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Business( $response->getData() );
        }
        return null;
    }

    /**
     * Update business
     * @param  string $customer_id Customer ID
     * @param  string $business_id Business ID
     * @param  array  $payload     Request payload
     */
    public function update(string $customer_id, string $business_id, array $payload = []): ?Business {
        $response = $this->client->patchRequest("/customers/{$customer_id}/businesses/{$business_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Business( $response->getData() );
        }
        return null;
    }
}

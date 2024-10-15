<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\DiscountCollection;
use Paddlin\Entities\Discount;

class Discounts extends AbstractResource {

    /**
     * List discounts
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): DiscountCollection {
        $response = $this->client->getRequest('/discounts', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Discount($properties);
            }
        }
        # Build collection
        $collection = new DiscountCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get discount
     * @param  string $discount_id  Discount ID
     * @param  array  $parameters   Query parameters
     */
    public function get(string $discount_id, array $parameters = []): ?Discount {
        $response = $this->client->getRequest("/discounts/{$discount_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Discount( $response->getData() );
        }
        return null;
    }

    /**
     * Create discount
     * @param  array  $payload  Request payload
     */
    public function create(array $payload = []): ?Discount {
        $response = $this->client->postRequest('/discounts', $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Discount( $response->getData() );
        }
        return null;
    }

    /**
     * Update discount
     * @param  string $discount_id  Discount ID
     * @param  array  $payload      Request payload
     */
    public function update(string $discount_id, array $payload = []): ?Discount {
        $response = $this->client->patchRequest("/discounts/{$discount_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Discount( $response->getData() );
        }
        return null;
    }
}

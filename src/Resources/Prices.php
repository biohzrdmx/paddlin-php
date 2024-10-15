<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\PriceCollection;
use Paddlin\Entities\Price;

class Prices extends AbstractResource {

    /**
     * List prices
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): PriceCollection {
        $response = $this->client->getRequest('/prices', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Price($properties);
            }
        }
        # Build collection
        $collection = new PriceCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get price
     * @param  string $price_id  Price ID
     * @param  array  $parameters  Query parameters
     */
    public function get(string $price_id, array $parameters = []): ?Price {
        $response = $this->client->getRequest("/prices/{$price_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Price( $response->getData() );
        }
        return null;
    }

    /**
     * Create price
     * @param  array  $payload  Request payload
     */
    public function create(array $payload = []): ?Price {
        $response = $this->client->postRequest('/prices', $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Price( $response->getData() );
        }
        return null;
    }

    /**
     * Update price
     * @param  string $price_id  Price ID
     * @param  array  $payload     Request payload
     */
    public function update(string $price_id, array $payload = []): ?Price {
        $response = $this->client->patchRequest("/prices/{$price_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Price( $response->getData() );
        }
        return null;
    }
}

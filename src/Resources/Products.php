<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\ProductCollection;
use Paddlin\Entities\Product;

class Products extends AbstractResource {

    /**
     * List products
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): ProductCollection {
        $response = $this->client->getRequest('/products', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Product($properties);
            }
        }
        # Build collection
        $collection = new ProductCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get product
     * @param  string $product_id  Product ID
     * @param  array  $parameters  Query parameters
     */
    public function get(string $product_id, array $parameters = []): ?Product {
        $response = $this->client->getRequest("/products/{$product_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Product( $response->getData() );
        }
        return null;
    }

    /**
     * Create product
     * @param  array  $payload  Request payload
     */
    public function create(array $payload = []): ?Product {
        $response = $this->client->postRequest('/products', $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Product( $response->getData() );
        }
        return null;
    }

    /**
     * Update product
     * @param  string $product_id  Product ID
     * @param  array  $payload     Request payload
     */
    public function update(string $product_id, array $payload = []): ?Product {
        $response = $this->client->patchRequest("/products/{$product_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Product( $response->getData() );
        }
        return null;
    }
}

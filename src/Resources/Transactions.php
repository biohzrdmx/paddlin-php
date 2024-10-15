<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\TransactionCollection;
use Paddlin\Entities\Invoice;
use Paddlin\Entities\Transaction;

class Transactions extends AbstractResource {

    /**
     * List transactions
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): TransactionCollection {
        $response = $this->client->getRequest('/transactions', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Transaction($properties);
            }
        }
        # Build collection
        $collection = new TransactionCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get transaction
     * @param  string $transaction_id  Transaction ID
     * @param  array  $parameters      Query parameters
     */
    public function get(string $transaction_id, array $parameters = []): ?Transaction {
        $response = $this->client->getRequest("/transactions/{$transaction_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Transaction( $response->getData() );
        }
        return null;
    }

    /**
     * Create transaction
     * @param  array  $payload  Request payload
     */
    public function create(array $payload = []): ?Transaction {
        $response = $this->client->postRequest('/transactions', $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Transaction( $response->getData() );
        }
        return null;
    }

    /**
     * Update transaction
     * @param  string $transaction_id  Transaction ID
     * @param  array  $payload         Request payload
     */
    public function update(string $transaction_id, array $payload = []): ?Transaction {
        $response = $this->client->patchRequest("/transactions/{$transaction_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Transaction( $response->getData() );
        }
        return null;
    }

    /**
     * Preview transaction
     * @param  array  $payload     Request payload
     */
    public function preview(array $payload = []): ?Transaction {
        $response = $this->client->postRequest("/transactions/preview", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Transaction( $response->getData() );
        }
        return null;
    }

    /**
     * Get transaction invoice
     * @param  string $transaction_id  Transaction ID
     */
    public function invoice(string $transaction_id): ?Invoice {
        $response = $this->client->getRequest("/transactions/{$transaction_id}/invoice");
        # Get items
        if ( $response->hasData() ) {
            return new Invoice( $response->getData() );
        }
        return null;
    }
}

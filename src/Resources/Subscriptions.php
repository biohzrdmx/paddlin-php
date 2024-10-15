<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\SubscriptionCollection;
use Paddlin\Entities\Subscription;
use Paddlin\Entities\Transaction;

class Subscriptions extends AbstractResource {

    /**
     * List subscriptions
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): SubscriptionCollection {
        $response = $this->client->getRequest('/subscriptions', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Subscription($properties);
            }
        }
        # Build collection
        $collection = new SubscriptionCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get subscription
     * @param  string $subscription_id  Subscription ID
     * @param  array  $parameters       Query parameters
     */
    public function get(string $subscription_id, array $parameters = []): ?Subscription {
        $response = $this->client->getRequest("/subscriptions/{$subscription_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Update subscription
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function update(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->patchRequest("/subscriptions/{$subscription_id}", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Preview subscription update
     * @param  array  $payload     Request payload
     */
    public function previewUpdate(array $payload = []): ?Subscription {
        $response = $this->client->patchRequest("/subscriptions/preview", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Get payment method change transaction
     * @param  string $subscription_id  Subscription ID
     */
    public function getPaymentMethodChangeTransaction(string $subscription_id): ?Transaction {
        $response = $this->client->getRequest("/subscriptions/{$subscription_id}/update-payment-method-transaction");
        # Get items
        if ( $response->hasData() ) {
            return new Transaction( $response->getData() );
        }
        return null;
    }

    /**
     * Create one-time charge
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function createOneTimeCharge(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/charge", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Preview one-time charge
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function previewOneTimeCharge(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/charge/preview", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Activate subscription
     * @param  string $subscription_id  Subscription ID
     */
    public function activate(string $subscription_id): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/activate");
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Pause subscription
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function pause(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/pause", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Resume subscription
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function resume(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/resume", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }

    /**
     * Cancel subscription
     * @param  string $subscription_id  Subscription ID
     * @param  array  $payload          Request payload
     */
    public function cancel(string $subscription_id, array $payload = []): ?Subscription {
        $response = $this->client->postRequest("/subscriptions/{$subscription_id}/cancel", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new Subscription( $response->getData() );
        }
        return null;
    }
}

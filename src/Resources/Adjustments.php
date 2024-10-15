<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Collections\AdjustmentCollection;
use Paddlin\Entities\Adjustment;
use Paddlin\Entities\CreditNote;

class Adjustments extends AbstractResource {

    /**
     * List adjustments
     * @param  array  $parameters Query parameters
     */
    public function list(array $parameters = []): AdjustmentCollection {
        $response = $this->client->getRequest('/adjustments', $parameters);
        # Get items
        $items = [];
        if ( $response->hasData() ) {
            foreach($response->getData() as $properties) {
                $items[] = new Adjustment($properties);
            }
        }
        # Build collection
        $collection = new AdjustmentCollection($this, $items, $response->getPagination());
        return $collection;
    }

    /**
     * Get adjustment
     * @param  string $adjustment_id Adjustment ID
     * @param  array  $parameters    Query parameters
     */
    public function get(string $adjustment_id, array $parameters = []): ?Adjustment {
        $response = $this->client->getRequest("/adjustments/{$adjustment_id}", $parameters);
        # Get items
        if ( $response->hasData() ) {
            return new Adjustment( $response->getData() );
        }
        return null;
    }

    /**
     * Get adjustment
     * @param  string $adjustment_id Adjustment ID
     */
    public function creditNote(string $adjustment_id): ?CreditNote {
        $response = $this->client->getRequest("/adjustments/{$adjustment_id}/credit-note");
        # Get items
        if ( $response->hasData() ) {
            return new CreditNote( $response->getData() );
        }
        return null;
    }
}

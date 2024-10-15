<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Entities\PricingPreview;

class PricingPreviews extends AbstractResource {

    /**
     * Preview prices
     * @param  array  $payload     Request payload
     */
    public function previewPrices(array $payload = []): ?PricingPreview {
        $response = $this->client->postRequest("/pricing-preview", $payload);
        # Get items
        if ( $response->hasData() ) {
            return new PricingPreview( $response->getData() );
        }
        return null;
    }
}

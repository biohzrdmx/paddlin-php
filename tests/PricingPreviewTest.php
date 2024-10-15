<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Entities\PricingPreview;

class PricingPreviewTest extends PaddlinTest {

    public function testPricingPreview() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/pricing-preview');
        $pricing_preview = $client->pricingPreviews->previewPrices($payload);
        $this->assertInstanceOf(PricingPreview::class, $pricing_preview);
        $this->assertEquals('34.232.58.13', $pricing_preview->customer_ip_address);
        $this->assertEquals('USD', $pricing_preview->currency_code);
    }
}

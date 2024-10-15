<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Entities;

class CreditNote extends AbstractEntity {

    /**
     * Get CreditNote URL
     */
    public function getUrl(): string {
        return $this->properties['url'] ?? '';
    }
}

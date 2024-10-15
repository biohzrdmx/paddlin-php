<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Resources;

use Paddlin\Client;

abstract class AbstractResource {

    /**
     * Client instance
     */
    protected Client $client;

    /**
     * Constructor
     * @param Client $client Client instance
     */
    public function __construct(Client $client) {
        $this->client = $client;
    }
}

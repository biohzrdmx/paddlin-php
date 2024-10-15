<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Collections;

use RuntimeException;

class BusinessCollection extends AbstractCollection {

    /**
     * @inheritdoc
     */
    protected function getListArgs(string $path, array $parameters): array {
        $parts = explode('/', $path);
        $customer_id = $parts[2] ?? null;
        if (! $customer_id ) {
            throw new RuntimeException('Invalid pagination link: customer_id can not be empty'); // @codeCoverageIgnore
        }
        return [
            $customer_id,
            $parameters,
        ];
    }
}

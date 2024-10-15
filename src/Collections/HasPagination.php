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

use Paddlin\Pagination;

trait HasPagination {

    /**
     * Pagination instance
     */
    protected ?Pagination $pagination;

    /**
     * Check if has more pages
     */
    public function hasMore(): bool {
        return $this->pagination ? $this->pagination->hasMore() : false;
    }

    /**
     * Get next page
     */
    public function next(): AbstractCollection {
        if ($this->pagination) {
            $next = $this->pagination->next();
            $path = parse_url($next, PHP_URL_PATH);
            $query = parse_url($next, PHP_URL_QUERY);
            if ($path && $query) {
                parse_str($query, $parameters);
                $args = $this->getListArgs($path, $parameters);
                return call_user_func_array([$this->resource, 'list'], $args); // @phpstan-ignore-line
            } else {
                throw new RuntimeException('Invalid next URI'); // @codeCoverageIgnore
            }
        } else {
            throw new RuntimeException('No pagination for this resource'); // @codeCoverageIgnore
        }
    }

    /**
     * Get list arguments
     * @param  string $path       Current request path
     * @param  array  $parameters Next link query parameters
     */
    protected function getListArgs(string $path, array $parameters): array {
        return [
            $parameters,
        ];
    }
}

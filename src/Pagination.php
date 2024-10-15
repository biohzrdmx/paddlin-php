<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin;

class Pagination {

    /**
     * Items per page
     */
    protected int $per_page;

    /**
     * Next page URI
     */
    protected string $next;

    /**
     * More pages flag
     */
    protected bool $has_more;

    /**
     * Estimated total items
     */
    protected int $estimated_total;

    /**
     * Constructor
     * @param array $pagination Pagination data
     */
    public function __construct(array $pagination = []) {
        $this->per_page = $pagination['per_page'] ?? 50;
        $this->next = $pagination['next'] ?? '';
        $this->has_more = $pagination['has_more'] ?? false;
        $this->estimated_total = $pagination['estimated_total'] ?? 0;
    }

    /**
     * Items per page
     */
    public function perPage(): int {
        return $this->per_page;
    }

    /**
     * Next page URI
     */
    public function next(): string {
        return $this->next;
    }

    /**
     * More pages flag
     */
    public function hasMore(): bool {
        return $this->has_more;
    }

    /**
     * Estimated total items
     */
    public function estimatedTotal(): int {
        return $this->estimated_total;
    }
}

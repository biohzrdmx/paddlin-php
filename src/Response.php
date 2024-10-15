<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin;

use Exception;
use RuntimeException;

use Psr\Http\Message\ResponseInterface;

class Response {

    /**
     * Request ID
     */
    protected string $id;

    /**
     * Data array
     */
    protected array $data;

    /**
     * Meta array
     */
    protected array $meta;

    /**
     * Pagination instance
     */
    protected ?Pagination $pagination = null;

    /**
     * Constructor
     * @param ResponseInterface $response ResponseInterface implementation
     */
    public function __construct(ResponseInterface $response) {
        $contents = $response->getBody()->getContents();
        try {
            $payload = json_decode($contents, true, flags: JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            throw new RuntimeException('Invalid server response', previous: $e);
        }
        if ( in_array($response->getStatusCode(), [200, 201]) ) {
            $this->id = $payload['meta']['request_id'] ?? '';
            $this->data = $payload['data'] ?? [];
            $this->meta = $payload['meta'] ?? [];
            # Get pagination, if any
            $pagination = $this->meta['pagination'] ?? null;
            if ($pagination) {
                $this->pagination = new Pagination($pagination);
            }
        } else {
            $payload = json_decode($contents, true, flags: JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
            $error = $payload['error'] ?? [];
            throw new ApiException($error, "An error ocurred: {$error['detail']} ({$error['documentation_url']})");
        }
    }

    /**
     * Check if response has data
     */
    public function hasData(): bool {
        return !!$this->data;
    }

    /**
     * Get response data
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * Check if response has meta
     */
    public function hasMeta(): bool {
        return !!$this->meta;
    }

    /**
     * Get response meta
     */
    public function getMeta(): array {
        return $this->meta;
    }

    /**
     * Check if response has pagination
     */
    public function hasPagination(): bool {
        return !!$this->pagination;
    }

    /**
     * Get Pagination instance
     */
    public function getPagination(): ?Pagination {
        return $this->pagination;
    }
}

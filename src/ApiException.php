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
use Throwable;

class ApiException extends Exception {

    /**
     * Error array
     */
    protected array $error;

    /**
     * Constructor
     * @param array          $error    Error array
     * @param string         $message  Exception message
     * @param int            $code     Exception code
     * @param Throwable|null $previous Previous exception
     */
    public function __construct(array $error, string $message = '', int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    /**
     * Get error array
     */
    public function getError(): array {
        return $this->error;
    }
}

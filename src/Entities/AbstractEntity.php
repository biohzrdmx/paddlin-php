<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Entities;

use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable {

    /**
     * Resource properties
     */
    protected array $properties;

    protected string $delimiter = '.';

    /**
     * Constructor
     * @param array $properties Resource properties
     */
    public function __construct(array $properties = []) {
        $this->properties = $properties;
    }

    /**
     * @inheritdoc
     */
    public function __get(string $name): mixed {
        return $this->properties[$name] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): mixed {
        return $this->properties;
    }

    /**
     * @inheritdoc
     */
    public function __debugInfo(): array {
        return $this->properties; // @codeCoverageIgnore
    }

    /**
     * Return the value of a given key
     * @param  int|string  $key     Property key
     * @param  mixed       $default Default value
     */
    public function get(int|string $key, mixed $default = null): mixed {
        if ($this->exists($this->properties, $key)) {
            return $this->properties[$key];
        }
        if (!is_string($key) || strpos($key, $this->delimiter) === false) {
            return $default;
        }
        $items = $this->properties;
        foreach (explode($this->delimiter, $key) as $segment) { // @phpstan-ignore-line
            if (!is_array($items) || !$this->exists($items, $segment)) {
                return $default;
            }
            $items = &$items[$segment];
        }
        return $items;
    }

    /**
     * Checks if the given key exists in the provided array.
     * @param  array       $array Array to validate
     * @param  int|string  $key   The key to look for
     */
    protected function exists($array, $key): bool {
        return array_key_exists($key, $array);
    }
}

<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Traversable;
use IteratorAggregate;
use JsonSerializable;

use Paddlin\Entities\AbstractEntity;
use Paddlin\Pagination;
use Paddlin\Resources\AbstractResource;

/**
 * @implements ArrayAccess<int, AbstractEntity>
 * @implements IteratorAggregate<int, AbstractEntity>
 */
abstract class AbstractCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable {

    use HasPagination;

    /**
     * Resource instance
     */
    protected AbstractResource $resource;

    /**
     * Collection items
     */
    protected array $items;

    /**
     * Constructor
     * @param AbstractResource $resource   Resource instance
     * @param array            $items      Collection items
     * @param Pagination       $pagination Pagination instance
     */
    public function __construct(AbstractResource $resource, array $items, ?Pagination $pagination = null) {
        $this->resource = $resource;
        $this->items = $items;
        $this->pagination = $pagination;
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): Traversable {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool {
        return isset( $this->items[$offset] ); // @codeCoverageIgnore
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed {
        return $this->items[$offset]; // @codeCoverageIgnore
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void {
        $this->items[$offset] = $value; // @codeCoverageIgnore
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void {
        unset( $this->items[$offset] ); // @codeCoverageIgnore
    }

    /**
     * @inheritdoc
     */
    public function count(): int {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): mixed {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function __debugInfo(): array {
        return $this->items; // @codeCoverageIgnore
    }
}

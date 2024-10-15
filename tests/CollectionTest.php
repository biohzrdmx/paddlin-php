<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Traversable;

use Paddlin\Collections\ProductCollection;

class CollectionTest extends PaddlinTest {

    public function testCollection() {
        $client = $this->getClient();
        #
        $collection = $client->products->list();
        $this->assertInstanceOf(ProductCollection::class, $collection);
        $this->assertEquals(6, $collection->count());
        $this->assertCount(6, $collection->jsonSerialize());
        $this->assertInstanceOf(Traversable::class, $collection->getIterator());
    }
}

<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use PHPUnit\Framework\TestCase;

use Paddlin\Pagination;

class PaginationTest extends TestCase {

    public function testPagination() {
        $pagination = new Pagination([
            'per_page' => 100,
            'next' => 'https://api.paddle.com/products?after=pro_01gsz4s0w61y0pp88528f1wvvb',
            'has_more' => true,
            'estimated_total' => 200,
        ]);
        $this->assertTrue($pagination->hasMore());
        $this->assertEquals(100, $pagination->perPage());
        $this->assertEquals('https://api.paddle.com/products?after=pro_01gsz4s0w61y0pp88528f1wvvb', $pagination->next());
        $this->assertEquals(200, $pagination->estimatedTotal());
    }
}

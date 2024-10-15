<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\ProductCollection;
use Paddlin\Entities\Product;

class ProductsTest extends PaddlinTest {

    public function testProductsList() {
        $client = $this->getClient();
        #
        $products = $client->products->list();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(6, $products);
        $this->assertFalse($products->hasMore());
        #
        $products = $products->next();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(6, $products);
    }

    public function testProductsGet() {
        $client = $this->getClient();
        #
        $product = $client->products->get('pro_01h7zcgmdc6tmwtjehp3sh7azf');
        $this->assertInstanceOf(Product::class, $product);
        #
        $this->assertEquals('pro_01h7zcgmdc6tmwtjehp3sh7azf', $product->id);
        $this->assertEquals('ChatApp Full', $product->name);
        $this->assertEquals('standard', $product->tax_category);
        $this->assertEquals('', $product->type);
        $this->assertEquals('Spend more time engaging with students with ChataApp Education. Includes features from our Pro plan, plus tools to help educators track student progress.', $product->description);
        $this->assertEquals('https://paddle-sandbox.s3.amazonaws.com/user/10889/2nmP8MQSret0aWeDemRw_icon1.png', $product->image_url);
        $this->assertEquals('active', $product->status);
        #
        $this->assertArrayHasKey('name', $product->jsonSerialize());
        #
        $this->assertEquals('ChatApp Full', $product->get('name'));
        $this->assertEquals('standard', $product->get('type', 'standard'));
        $this->assertEquals(true, $product->get('custom_data.features.reports'));
        $this->assertEquals([], $product->get('custom_data.extra', []));
    }

    public function testProductsCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/products');
        $product = $client->products->create($payload);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('ChatApp Full', $product->name);
    }

    public function testProductsUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/products');
        $product = $client->products->update('pro_01h7zcgmdc6tmwtjehp3sh7azf', $payload);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('ChatApp Pro', $product->name);
    }
}

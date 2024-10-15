<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\TransactionCollection;
use Paddlin\Entities\Invoice;
use Paddlin\Entities\Transaction;

class TransactionsTest extends PaddlinTest {

    public function testTransactionsList() {
        $client = $this->getClient();
        #
        $transactions = $client->transactions->list();
        $this->assertInstanceOf(TransactionCollection::class, $transactions);
        $this->assertCount(6, $transactions);
        $this->assertFalse($transactions->hasMore());
        #
        $transactions = $transactions->next();
        $this->assertInstanceOf(TransactionCollection::class, $transactions);
        $this->assertCount(6, $transactions);
    }

    public function testTransactionsGet() {
        $client = $this->getClient();
        #
        $transaction = $client->transactions->get('txn_01hen7bxc1p8ep4yk7n5jbzk9r');
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('billed', $transaction->status);
    }

    public function testTransactionsCreate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/transactions');
        $transaction = $client->transactions->create($payload);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('billed', $transaction->status);
    }

    public function testTransactionsUpdate() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/transactions');
        $transaction = $client->transactions->update('txn_01hen7bxc1p8ep4yk7n5jbzk9r', $payload);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('325-10261', $transaction->invoice_number);
    }

    public function testTransactionsPreview() {
        $client = $this->getClient();
        #
        $payload = $this->getRequestPayload('POST', '/transactions/preview');
        $transaction = $client->transactions->preview($payload);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('dsc_01gtgztp8fpchantd5g1wrksa3', $transaction->discount_id);
    }

    public function testTransactionsInvoice() {
        $client = $this->getClient();
        #
        $invoice = $client->transactions->invoice('txn_01hen7bxc1p8ep4yk7n5jbzk9r');
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('https://paddle-invoice-service-pdfs.s3.amazonaws.com/invoices/00000/f64658f0-d8ef-41cb-a916-d24d9b1e33bf/invoice_325-10001_Bluth.pdf', $invoice->getUrl());
    }
}

<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Tests;

use Paddlin\Collections\AdjustmentCollection;
use Paddlin\Entities\Adjustment;
use Paddlin\Entities\CreditNote;

class AdjustmentsTest extends PaddlinTest {

    public function testAdjustmentsList() {
        $client = $this->getClient();
        #
        $adjustments = $client->adjustments->list();
        $this->assertInstanceOf(AdjustmentCollection::class, $adjustments);
        $this->assertCount(5, $adjustments);
        $this->assertFalse($adjustments->hasMore());
        #
        $adjustments = $adjustments->next();
        $this->assertInstanceOf(AdjustmentCollection::class, $adjustments);
        $this->assertCount(5, $adjustments);
    }

    public function testAdjustmentsGet() {
        $client = $this->getClient();
        #
        $adjustment = $client->adjustments->get('adj_01h8c65c2ggq5nxswnnwv78e75');
        $this->assertInstanceOf(Adjustment::class, $adjustment);
        $this->assertEquals('pending_approval', $adjustment->status);
    }

    public function testAdjustmentsCreditNote() {
        $client = $this->getClient();
        #
        $credit_note = $client->adjustments->creditNote('adj_01h8c65c2ggq5nxswnnwv78e75');
        $this->assertInstanceOf(CreditNote::class, $credit_note);
        $this->assertEquals('https://paddle-production-invoice-service-pdfs.s3.amazonaws.com/credit_notes/15839/crdnt_01j4scmgpbtbxap16573dtck9n/credit_notes_296-10016_Paddle-com.pdf', $credit_note->getUrl());
    }
}

<?php

namespace App\StateMachines\Invoice;

use App\Enums\InvoiceState;

class UncollectableInvoiceState extends BaseInvoiceState
{
    function pay() {
        $this->invoice->update(['status' => InvoiceState::Paid]);
    }

    function void() {
        $this->invoice->update(['status' => InvoiceState::Void]);
    }
}

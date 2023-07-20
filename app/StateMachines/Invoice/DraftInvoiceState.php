<?php

namespace App\StateMachines\Invoice;

use App\Enums\InvoiceState;

class DraftInvoiceState extends BaseInvoiceState
{
    function finalize() {
        $this->invoice->update(['status' => InvoiceState::Open]);
        /** Pseudo Code Below */
        Mail::send(new InvoiceDue($this->invoice));
    }
}

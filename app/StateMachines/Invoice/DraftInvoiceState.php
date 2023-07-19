<?php /** @noinspection ALL */

namespace App\StateMachines\Invoice;

use App\Enums\InvoiceState;

class DraftInvoiceState extends BaseInvoiceState
{
    function finalize() {
        $this->invoice->update(['status' => InvoiceState::Open]);
        Mail::send(new InvoiceFinalize($this->invoice));
    }
}

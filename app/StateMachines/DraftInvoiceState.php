<?php

namespace App\StateMachines;

use App\Enums\InvoiceState;

class DraftInvoiceState extends BaseInvoiceState implements InvoiceStateContract
{
    public function getName(): InvoiceState
    {
        return InvoiceState::Draft;
    }

    public function finalize(): void
    {
        $this->invoice->update(['status' => InvoiceState::Open]);
    }

    public function color(): string
    {
        return 'yellow';
    }
}

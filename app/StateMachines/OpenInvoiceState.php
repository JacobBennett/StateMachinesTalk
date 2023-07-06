<?php


namespace App\StateMachines;

use App\Enums\InvoiceState;

class OpenInvoiceState extends BaseInvoiceState implements InvoiceStateContract
{
    public function getName(): InvoiceState
    {
        return InvoiceState::Open;
    }

    public function pay(): void
    {
        $this->invoice->update(['status' => InvoiceState::Paid]);
    }

    public function void(): void
    {
        $this->invoice->update(['status' => InvoiceState::Void]);
    }

    public function cancel(): void
    {
        $this->invoice->update(['status' => InvoiceState::Uncollectable]);
    }
}

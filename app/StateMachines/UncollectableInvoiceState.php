<?php


namespace App\StateMachines;


use App\Enums\InvoiceState;

class UncollectableInvoiceState extends BaseInvoiceState implements InvoiceStateContract
{

    public function getName(): InvoiceState
    {
        return InvoiceState::Uncollectable;
    }

    public function pay(): void
    {
        $this->invoice->update(['status' => InvoiceState::Paid]);
    }

    public function void(): void
    {
        $this->invoice->update(['status' => InvoiceState::Void]);
    }
}

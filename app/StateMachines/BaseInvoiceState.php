<?php

namespace App\StateMachines;

use App\Models\Invoice;
class BaseInvoiceState
{
    public function __construct(public Invoice $invoice)
    {
    }

    public function finalize(): void
    {
        throw new \Exception('There is no mapping for the finalize event from the current state '. $this->getName()->value);
    }

    public function pay(): void
    {
        throw new \Exception('There is no mapping for the pay event from the current state '. $this->getName()->value);
    }

    public function void(): void
    {
        throw new \Exception('There is no mapping for the void event from the current state '. $this->getName()->value);
    }

    public function cancel(): void
    {
        throw new \Exception('There is no mapping the cancel event from the current state '. $this->getName()->value);
    }
}

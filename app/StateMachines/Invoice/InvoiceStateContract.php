<?php


namespace App\StateMachines\Invoice;


use App\Models\Invoice;
use App\Enums\InvoiceState;

interface InvoiceStateContract
{
    public function __construct(Invoice $invoice);
    public function finalize(): void;
    public function pay(): void;
    public function void(): void;
    public function cancel(): void;
}

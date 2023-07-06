<?php


namespace App\StateMachines;


use App\Models\Invoice;
use App\Enums\InvoiceState;

interface InvoiceStateContract
{
    public function __construct(Invoice $invoice);
    public function getName(): InvoiceState;

    public function color(): string;
    public function finalize(): void;
    public function pay(): void;
    public function void(): void;
    public function cancel(): void;
}

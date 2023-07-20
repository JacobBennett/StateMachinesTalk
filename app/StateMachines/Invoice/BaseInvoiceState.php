<?php

namespace App\StateMachines\Invoice;

use App\Models\Invoice;

class BaseInvoiceState implements InvoiceStateContract
{
    function __construct(public Invoice $invoice) {}
    function finalize() { throw new \Exception(); }
    function pay() { throw new \Exception(); }
    function void() { throw new \Exception(); }
    function cancel() { throw new \Exception(); }
}

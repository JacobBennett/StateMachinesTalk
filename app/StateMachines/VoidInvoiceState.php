<?php


namespace App\StateMachines;


use App\Enums\InvoiceState;

class VoidInvoiceState extends BaseInvoiceState implements InvoiceStateContract
{

    public function getName(): InvoiceState
    {
        return InvoiceState::Void;
    }
}

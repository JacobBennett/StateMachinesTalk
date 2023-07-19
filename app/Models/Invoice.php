<?php

namespace App\Models;

use App\Enums\InvoiceState;
use http\Exception\InvalidArgumentException;
use App\StateMachines\Invoice\PaidInvoiceState;
use App\StateMachines\Invoice\VoidInvoiceState;
use App\StateMachines\Invoice\OpenInvoiceState;
use App\StateMachines\Invoice\DraftInvoiceState;
use App\StateMachines\Invoice\InvoiceStateContract;
use App\StateMachines\Invoice\UncollectableInvoiceState;

class Invoice extends BaseModel
{
    protected $attributes = [
        'status' => InvoiceState::Draft,
    ];

    protected $casts = [
        'status' => InvoiceState::class,
    ];

    public function state(): InvoiceStateContract
    {
        return match ($this->status) {
            InvoiceState::Draft => new DraftInvoiceState($this),
            InvoiceState::Open => new OpenInvoiceState($this),
            InvoiceState::Paid => new PaidInvoiceState($this),
            InvoiceState::Void => new VoidInvoiceState($this),
            InvoiceState::Uncollectable => new UncollectableInvoiceState($this),
            default => throw new InvalidArgumentException('Invalid status'),
        };
    }
}

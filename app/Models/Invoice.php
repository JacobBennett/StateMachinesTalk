<?php

namespace App\Models;

use App\Enums\InvoiceState;
use App\StateMachines\InvoiceStateContract;
use App\StateMachines\OpenInvoiceState;
use App\StateMachines\PaidInvoiceState;
use App\StateMachines\VoidInvoiceState;
use App\StateMachines\DraftInvoiceState;
use http\Exception\InvalidArgumentException;
use App\StateMachines\UncollectableInvoiceState;
use Illuminate\Database\Eloquent\Model;

class Invoice extends BaseModel
{
    protected $attributes = [
        'status' => InvoiceState::Draft,
    ];

    public function status(): InvoiceStateContract
    {
        $stateClass = match ($this->status) {
            InvoiceState::Draft => DraftInvoiceState::class,
            InvoiceState::Open => OpenInvoiceState::class,
            InvoiceState::Paid => PaidInvoiceState::class,
            InvoiceState::Void => VoidInvoiceState::class,
            InvoiceState::Uncollectable => UncollectableInvoiceState::class,
            default => throw new InvalidArgumentException('Invalid status'),
        };

        return new $stateClass($this);
    }
}

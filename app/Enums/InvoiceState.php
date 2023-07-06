<?php


namespace App\Enums;


enum InvoiceState: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Paid = 'paid';
    case Void = 'void';
    case Uncollectable = 'uncollectable';
}

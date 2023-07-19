<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PayInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        $invoice->state()->pay();

        return view('invoice.thanks', ['invoice' => $invoice]);
    }
}

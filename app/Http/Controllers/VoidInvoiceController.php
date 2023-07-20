<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoidInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        $invoice->state()->void();

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

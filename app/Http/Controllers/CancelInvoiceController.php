<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class CancelInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        $invoice->state()->cancel();

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class FinalizeInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        $invoice->state()->finalize();

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

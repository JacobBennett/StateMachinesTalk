<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoidInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        // Can only void an invoice if it has been finalized
        // but was not paid and is not cancelled
        if (
            filled($invoice->finalized_at) &&
            blank($invoice->paid_at) &&
            blank($invoice->cancelled_at)
        )
            $invoice->update(['void_at' => now()]);

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

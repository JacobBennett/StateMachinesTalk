<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class FinalizeInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        if (blank($invoice->finalized_at) || blank($invoice->paid_at)) {
            abort(403, 'Invoice is not in a finalizable state');
        }

        $invoice->update(['finalized_at' => now()]);
        $invoice->customer->notify(new InvoiceFinalized($invoice));

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

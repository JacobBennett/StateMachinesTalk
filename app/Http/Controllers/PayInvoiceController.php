<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PayInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        if (
            blank($invoice->finalized_at) ||
            filled($invoice->paid_at) ||
            filled($invoice->uncollectable_at) ||
            filled($invoice->void_at)
        ) {
            abort(403, 'Invoice cannot be paid');
        }
        $invoice->update(['paid_at' => now()]);
        $invoice->user->notify(new InvoicePaid($invoice));

        return view('invoice.thanks', ['invoice' => $invoice]);
    }
}

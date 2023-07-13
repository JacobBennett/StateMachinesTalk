<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class CancelInvoiceController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        if (
            filled($invoice->finalized_at) &&
            blank($invoice->paid_at)
        ) {
            $invoice->update(['cancelled_at' => now()]);
        } else {
            throw new \LogicException('Invoice is not in a cancellable state.');
        }

        return view('invoice.show', ['invoice' => $invoice]);
    }
}

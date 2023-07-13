<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function store(CreateInvoiceRequest $request)
    {
        Invoice::create($request->validate());
        return redirect()->route('invoice.index');
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        if (filled($invoice->finalized_at) || filled($invoice->paid_at)) {
            abort(403, 'Invoice is not in a updatable state');
        }

        $invoice->update($request->validate());
        return redirect()->route('invoice.show', $invoice);
    }
}

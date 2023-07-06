<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('machine/{event}', function ($event) {
    $invoice = Invoice::create();

    $invoice->status()->finalize();
    $invoice->status()->cancel();
//    $invoice->status()->void();
    $invoice->status()->pay();

    dd($invoice->status()->getName()->value);

});

Route::get('small/{event}', function ($event) {

    // In this approach, we are using the controllers
    // to define the events that can be triggered
    // on our model.
    // The issue is that there is not enforcement
    // for when these events can be triggered.
    // We end up determining the state implicitly
    // at the time of the event, and doing conditional
    // logic to determine if the event can be triggered.

    // In addition, if we ever need to transition to a
    // new state, we need to find all the places where
    // we are handling transitions and be sure to update
    // the implicit logic there as well.

    // What about if we wanted to add a Command that would
    // handle updating our invoices to uncollectable
    // after a period of inactivity on the invoice?
    // The logic around if that is possible now has
    // to be duplicated across all locations where
    // the transition is handled.

    // Also what about querying for invoices that are open
    // but have not yet been paid. How do we determine which
    // Invoices to query for. We can't query for only those
    // that are finalized, because they could also be paid
    // so we end up making these `isOpen` method on the
    // Model to use as a way to query for these
    // but we still have to hydrate the entire model
    // so that we can query for these things

    $invoice = Invoice::create();

    // Initial State
//    $invoice->update(['status' => 'draft']);

    // In the FinalizeInvoiceController
    // Open
    $invoice->update(['is_finalized' => 1]);
    $invoice->update(['finalized_at' => now()]);

    // In the PayInvoiceController
    // Paid
    $invoice->update(['is_paid' => now()]);
    $invoice->update(['paid_at' => now()]);

    // In the VoidInvoiceController
    // Void
    $invoice->update(['is_void' => 1]);

    // In the CancelInvoiceController
    // Uncollectable
    $invoice->update(['is_cancelled' => 1]);

});

Route::get('small/{event}', function ($event) {

    // same as previous approach but with some conditional logic

    $invoice = Invoice::create();

    // Initial State
//    $invoice->update(['status' => 'draft']);

    // In the FinalizeInvoiceController
    // Open
    if (!$invoice->is_finalized && !$invoice->is_paid && !$invoice->is_void && !$invoice->is_cancelled) {
        $invoice->update(['is_finalized' => 1]);
        $invoice->update(['finalized_at' => now()]);
    }

    // In the PayInvoiceController
    // Paid
    $invoice->update(['is_paid' => now()]);
    $invoice->update(['paid_at' => now()]);

    // In the VoidInvoiceController
    // Void
    $invoice->update(['is_void' => 1]);

    // In the CancelInvoiceController
    // Uncollectable
    $invoice->update(['is_cancelled' => 1]);

});

Route::get('small/{event}', function ($event) {

    // In this approach, we are using the controllers
    // to define the events that can be triggered
    // on our model.
    // The issue is that there is not enforcement
    // for when these events can be triggered.
    // We end up determining the state implicitly
    // at the time of the event, and doing conditional
    // logic to determine if the event can be triggered.

    // In addition, if we ever need to transition to a
    // new state, we need to find all the places where
    // we are handling transitions and be sure to update
    // the implicit logic there as well.

    // What about if we wanted to add a Command that would
    // handle updating our invoices to uncollectable
    // after a period of inactivity on the invoice?
    // The logic around if that is possible now has
    // to be duplicated across all locations where
    // the transition is handled.

    $invoice = Invoice::create();

    // Initial State
    $invoice->update(['status' => 'draft']);

    // In the FinalizeInvoiceController
    $invoice->update(['status' => 'open']);

    // In the VoidInvoiceController
    $invoice->update(['status' => 'void']);

    // In the PayInvoiceController
    $invoice->update(['status' => 'paid']);

    // In the CancelInvoiceController
    $invoice->update(['status' => 'uncollectable']);

});

Route::get('eventFirst/{event}', function ($event) {

    // In this example we have replaced implicit state
    // with a status value that should keep track of the
    // current state of things.

    // This is better because we no longer have to use
    // timestamps and boolean flags to determine the status
    // of our Invoice, but we do have to deal with
    // nasty conditionals which are difficult to reason
    // about, provide not type safety, and are difficult
    // to test.

    // In this method we are working off of the event
    // first, then checking the current status to determine
    // what should happen next. This is a bit better than
    // the previous method, but we still have the issue
    // of implicit logic.

    // We are also still duplicating logic across all
    // locations where the transition is handled.

    $invoice = Invoice::create();

    if ($event === 'finalize' && $invoice->status === 'draft') {
        $invoice->update(['status' => 'open']);
    }

    if ($event === 'pay' && $invoice->status === 'open') {
        $invoice->update(['status' => 'paid']);
    }

    if ($event === 'pay' && $invoice->status === 'uncollectable') {
        $invoice->update(['status' => 'paid']);
    }

    if ($event === 'void' && $invoice->status === 'open') {
        $invoice->update(['status' => 'void']);
    }

    if ($event === 'void' && $invoice->status === 'uncollectable') {
        $invoice->update(['status' => 'void']);
    }

    if ($event === 'cancel' && $invoice->status === 'open') {
        $invoice->update(['status' => 'uncollectable']);
    }

});

Route::get('eventFirstMatch/{event}', function ($event) {
    // This way switches on the events
    // This allows us to collapse our conditional checks into
    // a single match statement, but the logic for each state
    // is now smeared around the code. It is hard
    // to follow where we are in the state diagram to determine
    // if we have implemented all transitions that we need.

    $invoice = Invoice::create();

    match($event) {
        'finalize' => match($invoice->status) {
            'draft' => $invoice->update(['status' => 'open']),
            default => throw new \InvalidArgumentException('Invalid event for current state')
        },
        'pay' => match($invoice->status) {
            'open' => $invoice->update(['status' => 'paid']),
            'uncollectable' => $invoice->update(['status' => 'paid']),
            default => throw new \InvalidArgumentException('Invalid event for current state')
        },
        'void' => match($invoice->status) {
            'open' => $invoice->update(['status' => 'void']),
            'uncollectable' => $invoice->update(['status' => 'void']),
            default => throw new \InvalidArgumentException('Invalid event for current state')
        },
        'cancel' => match($invoice->status) {
            'open' => $invoice->update(['status' => 'uncollectable']),
            default => throw new \InvalidArgumentException('Invalid event for current state')
        },
        default => throw new \InvalidArgumentException('Invalid event')
    };

});

Route::get('stateFirst/{event}', function ($event) {

    // In this method we are working off of the state
    // first, then checking the event to determine
    // what should happen next. This is much better than
    // the previous method, but these match statements
    // don't provide any type safety, and the amount
    // of code we can embed in each match statement
    // is limited before it's going to get really messy.


    $invoice = Invoice::create();

    match($invoice->status) {
        'draft' => match($event) {
            'finalize' => $invoice->update(['status' => 'open']),
            default => throw new \InvalidArgumentException('bad event'),
        },
        'open' => match($event) {
            'pay' => $invoice->update(['status' => 'paid']),
            'void' => $invoice->update(['status' => 'void']),
            'cancel' => $invoice->update(['status' => 'uncollectable']),
            default => throw new \InvalidArgumentException('bad event'),
        },
        'uncollectable' => match($event) {
            'pay' => $invoice->update(['status' => 'paid']),
            'void' => $invoice->update(['status' => 'void']),
            default => throw new \InvalidArgumentException('bad event'),
        },
        'void' => throw new \InvalidArgumentException('void is a final state'),
        'paid' => throw new \InvalidArgumentException('paid is a final state'),
        default => throw new \InvalidArgumentException('Invalid state for Invoice Status'),
    };

});

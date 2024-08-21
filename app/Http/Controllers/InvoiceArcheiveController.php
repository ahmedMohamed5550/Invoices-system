<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceArcheiveController extends Controller
{
    public function allInvoicesArcheive(){
        $invoices = Invoice::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('invoices.archeive', compact('invoices'));
    }

    public function retreiveInvoice($id)
    {
        $invoice = Invoice::withTrashed()->findOrFail($id);
        $invoice->restore();

        session()->flash('retreive', 'تم الاسترجاع بنجاح');
        return redirect()->back();
    }
}

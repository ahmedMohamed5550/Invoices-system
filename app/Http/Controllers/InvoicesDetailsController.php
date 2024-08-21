<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_Attachments;
use App\Models\Invoices_Details;
use Illuminate\Http\Request;

class InvoicesDetailsController extends Controller
{
    public function edit($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        $details  = invoices_details::where('invoice_id',$id)->get();
        $attachments  = invoice_attachments::where('invoice_id',$id)->get();
        // dd([$invoices,$details,$attachments]);

        return view('invoices.details',compact('invoices','details','attachments'));
    }
}

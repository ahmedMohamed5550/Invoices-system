<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Imports\InvoicesImport;
use App\Models\Invoice;
use App\Models\Invoice_Attachments;
use App\Models\Invoices_Details;
use App\Models\Section;
use App\Notifications\InvoiceCreate;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function index() {
        $invoices = Invoice::where('deleted_at',null)->orderBy('created_at', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }

    public function showAllInvoicesPaid() {
        $invoices = Invoice::where('Value_Status',1)->orderBy('created_at', 'desc')->get();
        return view('invoices.paid', compact('invoices'));
    }

    public function showAllInvoicesPartialyPaid() {
        $invoices = Invoice::where('Value_Status',3)->orderBy('created_at', 'desc')->get();
        return view('invoices.partialypaid', compact('invoices'));
    }

    public function showAllInvoicesUnPaid() {
        $invoices = Invoice::where('Value_Status',2)->orderBy('created_at', 'desc')->get();
        return view('invoices.unpaid', compact('invoices'));
    }

    public function create(){
        $sections = Section::all();
        return view('invoices.create',compact('sections'));
    }

    public function store(Request $request){

        // create in invoice table
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->section_id,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        // insert data from invoices when create to invoice_details

        $invoice_id = Invoice::latest()->first()->id; // get the last id in invoice
        $section_name = Invoice::latest()->first()->section->section_name;

        $invoice_details = Invoices_Details::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $section_name,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'Payment_Date' => $request->Payment_Date,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        // insert data from invoices when create to invoice_attachment

        $invoice_number = Invoice::latest()->first()->invoice_number; // get invoice number

        if($request->has('file_name')){
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();

            $invoice_attachment = Invoice_Attachments::create([
                'file_name' => $file_name,
                'invoice_number' => $invoice_number,
                'Created_by' => Auth::user()->name,
                'invoice_id' => $invoice_id,
            ]);

            // move pic
            $imageName = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('Attachments/' . $invoice_number), $imageName);
        }


        // notfication
        $users = Auth::user()->role('owner')->get();
        // dd($users);
        foreach($users as $user){
            Notification::send($user,new InvoiceCreate(
                $invoice_id,
                $invoice->invoice_number,
                $invoice->invoice_Date,
                $section_name,
                $invoice_details->user,
            ));
        }



        session()->flash('Add', 'تمت الاضافه بنجاح');
        return back();
    }

    public function getProducts($id){
        $products = DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }

    public function edit($id){
        $invoices = Invoice::where('id',$id)->first();
        $sections = Section::where('id',$invoices->section_id);
        return view('invoices.edit',compact('invoices','sections'));
    }

    public function update(Request $request,$id){
        $invoices = Invoice::findOrFail($id);

        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->section_id ?? $invoices->section_id,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        // Flash success message and redirect back
        session()->flash('edit', 'تم التعديل بنجاح');
        return redirect()->back();
    }

    public function destroy($id){
        $invoices = Invoice::findOrFail($id);
        $invoiceAttachment = Invoice_Attachments::where('invoice_id', $id)->first();

        $invoices->forceDelete();


        // delete attachment to invoice if find
        if ($invoiceAttachment) {
            $folderPath = public_path('Attachments/' . $invoiceAttachment->invoice_number);

            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }
        }

        session()->flash('delete', 'تمت الحذف بنجاح');
        return redirect()->back();
    }

    public function archeiveInvoice($id){
        $invoices = Invoice::findOrFail($id);
        $invoices->delete();

        session()->flash('archeive', 'تمت الارشفه بنجاح');
        return redirect()->back();
    }

    public function showstatus($id){
        $invoices = Invoice::findOrFail($id);
        return view('invoices.status',compact('invoices'));
    }

    public function updatestatus($id, Request $request)
    {
        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoices_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            Invoices_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        session()->flash('Status_Update','تم تغيير حالة الدفع بنجاح');
        return redirect('invoices/index');

    }

    public function printInvoice($id){
        $invoices = Invoice::findOrFail($id);
        return view('invoices.print',compact('invoices'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new InvoicesImport, $request->file('file'));
        return redirect('invoices/index')->with('import', 'تم استدعاء ملف Excel بنجاح');
    }

    public function exportExcel()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }


}

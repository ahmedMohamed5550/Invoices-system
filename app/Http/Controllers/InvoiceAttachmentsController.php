<?php

namespace App\Http\Controllers;

use App\Models\Invoice_Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentsController extends Controller
{
    public function download($invoice_number, $file_name)
    {
        // Construct the file path
        $filePath = public_path('Attachments/' . $invoice_number . '/' . $file_name);

        // Check if the file exists
        if (!file_exists($filePath)) {
            return abort(404, 'File not found.');
        }

        // Return the file as a response with forced download
        return response()->download($filePath);
    }


    public function openFile($invoice_number, $file_name)
    {
        // Construct the file path
        $filePath = 'Attachments/' . $invoice_number . '/' . $file_name;

        // Check if the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            return abort(404, 'File not found.');
        }

        // Determine the MIME type of the file
        $mimeType = Storage::disk('public')->mimeType($filePath);

        // Return the file as a response
        return response()->file(
            Storage::disk('public')->path($filePath),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline' // or 'attachment' to force download
            ]
        );
    }



    public function destroy($id)
    {
        // Find the attachment by invoice_id
        $invoiceAttachment = Invoice_Attachments::where('invoice_id', $id)->first();

        // Delete the attachment record from the database
        $invoiceAttachment->delete();

        // Construct the file path
        $filePath = public_path('Attachments/' . $invoiceAttachment->invoice_number . '/' . $invoiceAttachment->file_name);

        // Delete the file
        unlink($filePath);

        // Flash a success message and redirect back
        session()->flash('delete', 'تم الحذف بنجاح');
        return redirect()->back();
    }

    public function store(Request $request){

        $this->validate($request, [

            'file_name' => 'mimes:pdf,jpeg,png,jpg',

            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);

        $image = $request->file('file_name');
        $file_name = $image->getClientOriginalName();

        $invoice_attachment = Invoice_Attachments::create([
            'file_name' => $file_name,
            'invoice_number' => $request->invoice_number,
            'Created_by' => Auth::user()->name,
            'invoice_id' => $request->invoice_id,
        ]);

        // move pic
        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/' . $request->invoice_number), $imageName);

        session()->flash('Add', 'تمت الاضافه بنجاح');
        return back();
    }

}

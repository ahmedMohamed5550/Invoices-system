<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function getAllInvoices(){
        return view('reports.invoices');
    }

    public function search_invoices(Request $request){
        $rdio = $request->rdio;

        switch ($rdio) {
            case 1:
                if ($request->type && $request->start_at =='' && $request->end_at =='') {

                    $invoices = Invoice::where('Status','=',$request->type)->get();
                    $type = $request->type;
                    return view('reports.invoices',compact('type','invoices'));
                 }

                 // في حالة تحديد تاريخ استحقاق
                 else {

                   $start_at = date($request->start_at);
                   $end_at = date($request->end_at);
                   $type = $request->type;

                   $invoices = Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
                   return view('reports.invoices',compact('type','start_at','end_at','invoices'));

                 }
                break;

            case 2:
                $invoices = Invoice::where('invoice_number','like','%'.$request->invoice_number.'%')->get();
                return view('reports.invoices',compact('invoices'));
                break;
        }
    }

    // get all customers

    public function getAllCustomers(){
        $sections = Section::all();
        return view('reports.customers',compact('sections'));
    }

    public function search_customers(Request $request){

        if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {

            $invoices = Invoice::where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections = Section::all();
            return view('reports.customers',compact('sections','invoices'));
        }

        else {
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections = Section::all();
            return view('reports.customers',compact('sections','invoices'));
        }

    }
}

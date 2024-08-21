<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(){

        $sections = Section::all();
        $products = Product::all();
        return view('products.index',compact(['sections','products']));
    }

    public function store(Request $request)
    {

        $request->validate([
            'product_name' => 'required|unique:products|max:255',
        ],[
            'product_name.required' =>'يرجي ادخال اسم القسم',
            'product_name.unique' =>'اسم القسم مسجل مسبقا',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);


        session()->flash('Add', 'تمت الاضافه بنجاح');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $id = $request->id;
        // Validate the incoming request data
        $request->validate([
            'product_name' => 'required|max:255|unique:products,product_name,' . $id,
            'description' => 'required',
            'section_id' => 'required|exists:sections,id'
        ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'description.required' => 'يرجي ادخال الملاحظات',
            'section_id.required' => 'يرجي اختيار القسم',
            'section_id.exists' => 'القسم المحدد غير موجود',
        ]);

        // Find the existing product by its ID
        $product = Product::findOrFail($id);

        // Update the product with the new data
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        // Flash success message and redirect back
        session()->flash('edit', 'تم التعديل بنجاح');
        return redirect()->back();
    }

    public function destroy(Request $request){
        $id = $request->id;
        $product = Product::findOrFail($id);
        $product->delete();
        session()->flash('delete', 'تمت الحذف بنجاح');
        return redirect()->back();
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function index(){
        $sections = Section::all();
        // dd($sections);
        return view("sections.index", compact("sections"));
    }

    public function store(Request $request)
    {

        $request->validate([
            'section_name' => 'required|unique:sections|max:255',
        ],[
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
        ]);

        Section::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => Auth::user()->name
        ]);

        session()->flash('Add', 'تمت الاضافه بنجاح');
        return redirect()->back();

    }

    public function update(Request $request){
        $id = $request->id;

        $request->validate([
            'section_name' => 'required|unique:sections|max:255'.$id,
            'description' => 'required'
        ],[
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال الملاحظات ',
        ]);
        $section = Section::findOrFail($id);

        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => Auth::user()->name
        ]);

        session()->flash('edit', 'تمت التعديل بنجاح');
        return redirect()->back();

    }

    public function destroy(Request $request){
        $id = $request->id;
        $section = Section::findOrFail($id);
        $section->delete();
        session()->flash('delete', 'تمت الحذف بنجاح');
        return redirect()->back();
    }
}

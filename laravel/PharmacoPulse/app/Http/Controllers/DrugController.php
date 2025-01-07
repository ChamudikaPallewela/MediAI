<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\drugs;
use App\Models\clinics;

class DrugController extends Controller
{
    public function index()
    {
        $drugs = drugs::all();
        return view('drugs.index', compact('drugs'));
    }

    public function create()
    {
        $clinics = clinics::all();
        return view('drugs.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'drug_name' => 'required',
            'description' => 'nullable',
            'expiry_date' => 'required|date',
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        $drug = drugs::create([
            'drug_name' => $request->input('drug_name'),
            'description' => $request->input('description'),
            'expiry_date' => $request->input('expiry_date'),
        ]);

        $drug->clinics()->attach($request->input('clinic_id'));

        return redirect()->route('drugs.index')->with('success', 'Drug added successfully');
    }

    public function edit($id)
    {
        $drug = drugs::find($id);
        $clinics = clinics::all();
        return view('drugs.edit', compact('drug', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'drug_name' => 'required',
            'description' => 'nullable',
            'expiry_date' => 'required|date',
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        $drug = drugs::find($id);
        $drug->update([
            'drug_name' => $request->input('drug_name'),
            'description' => $request->input('description'),
            'expiry_date' => $request->input('expiry_date'),
        ]);

        $drug->clinics()->sync([$request->input('clinic_id')]);

        return redirect()->route('drugs.index')->with('success', 'Drug updated successfully');
    }

    public function destroy($id)
    {
        $drug = drugs::find($id);
        $drug->clinics()->detach();
        $drug->delete();

        return redirect()->route('drugs.index')->with('success', 'Drug deleted successfully');
    }
}



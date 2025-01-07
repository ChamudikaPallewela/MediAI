<?php

namespace App\Http\Controllers;
use App\Http\Requests\ClinicStoreRequest;
use Illuminate\Http\Request;
use App\Models\clinics;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $clinics = clinics::all(); // Assuming 'Menu' is the model representing the menu items

    return view('clinic.index', compact('clinics'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Logic to prepare data and render the create menu form
        return view('clinic.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClinicStoreRequest $request)
{
    // Validate the request data
    $validatedData = $request->validated();

    // Create a new clinics instance with the validated data
    $clinic = new clinics($validatedData);

    // Save the clinics instance to the database
    $clinic->save();

    // Redirect to the index page with a success message
    return redirect()->route('clinic.index')->with('success', 'Clinic added successfully.');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(clinics $clinic)
    {

        return view('clinic.edit', compact('clinic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, clinics $clinic)
    {
        $request->validate([
            'clinic_name' => 'required',
        ]);
    
    
        $clinic->update([
            'clinic_name' => $request->clinic_name,
        ]);
    
        return redirect()->route('clinic.index')->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(clinics $clinic)
{
    $clinic->delete();
    return redirect()->route('clinic.index')->with('success', 'Clinic deleted successfully.');
}

}

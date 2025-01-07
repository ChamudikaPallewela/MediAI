<?php

// app/Http/Controllers/PrescriptionController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\drugs;
use App\Models\patient;
use App\Models\prescription;
use Illuminate\Http\Request;
use App\Models\MedicineUsage;

class PrescriptionController extends Controller
{
    public function index()
    {
        $this->deleteExpiredPrescriptions();

        $prescriptions = prescription::with('patient', 'drug')->get();
        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $patients = patient::all();
        $drugs = drugs::all();
        return view('prescriptions.create', compact('patients', 'drugs'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'drug_id' => 'required|exists:drugs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'dosage' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        // Save the prescription
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'drug_id' => $request->drug_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'dosage' => $request->dosage,
            'instructions' => $request->instructions,
        ]);

        // Save the medicine usage
        $startDate = Carbon::parse($request->start_date);
        $monthName = $startDate->format('F'); // Convert month number to month name (e.g., "January")

        MedicineUsage::create([
            'patient_id' => $request->patient_id,
            'drug_id' => $request->drug_id,
            'usage_date' => $startDate,
            'month' => $monthName, // Store the month as a string
        ]);

        // Redirect back with success message
        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully!');
    }

    public function show(prescription $prescription)
    {
        return view('prescriptions.show', compact('prescription'));
    }

    public function edit(prescription $prescription)
    {
        $patients = patient::all();
        $drugs = drugs::all();
        return view('prescriptions.edit', compact('prescription', 'patients', 'drugs'));
    }

    public function update(Request $request, prescription $prescription)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'drug_id' => 'required|exists:drugs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'dosage' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $prescription->update($request->all());

        return redirect()->route('prescriptions.index')->with('success', 'Prescription updated successfully.');
    }

    public function destroy(prescription $prescription)
    {
        $prescription->delete();

        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }
    private function deleteExpiredPrescriptions()
    {
        $now = Carbon::now('UTC');
        $expiredPrescriptions = prescription::where('end_date', '<=', $now)->get();

        foreach ($expiredPrescriptions as $expiredPrescription) {
            $expiredPrescription->delete();
        }
    }
}

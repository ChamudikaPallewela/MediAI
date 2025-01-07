<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\qrCode;
use App\Models\clinics;
use App\Models\patient;
use App\Models\prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use BaconQrCode\Encoder\QrCode as BaconQrCode;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clinics = clinics::all();
        return view('patients.create', compact('clinics'));
    }

    /**
     * Store a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string',
            'clinic_id' => 'required|exists:clinics,id',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'id_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        Patient::create($request->all());

        return redirect()->route('patients.index')->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        $clinics = clinics::all();
        return view('patients.edit', compact('patient', 'clinics'));
    }

    /**
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'patient_name' => 'required|string',
            'clinic_id' => 'required|exists:clinics,id',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'id_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified patient from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
    public function showDetails(Patient $patient)
{
    $prescriptions = prescription::with('drug', 'patient.clinic')->where('patient_id', $patient->id)->get();
    return view('patient_details', compact('patient', 'prescriptions'));
}
// public function generateQrCode(patient $patient)
// {
//     // Generate QR code value including patient ID and name
//     $qrCodeValue = "Patient ID: {$patient->id}, Name: {$patient->patient_name}";

//     // Fetch prescriptions for the patient with associated drug names and end dates
//     $prescriptions = $patient->prescriptions()->with('drug')->get();

//     // Calculate remaining days for each drug
//     $remainingDaysInfo = $prescriptions->map(function ($prescription) {
//         $remainingDays = Carbon::now()->diffInDays(Carbon::parse($prescription->end_date));
//         return "{$prescription->drug->drug_name}: {$remainingDays} days remaining";
//     });

//     // Concatenate drug names and remaining days to the QR code value
//     if ($remainingDaysInfo->isNotEmpty()) {
//         $qrCodeValue .= ", Prescription Details: " . $remainingDaysInfo->implode(', ');
//     }

//     // Make a request to an online QR code generator API
//     $response = Http::get('https://api.qrserver.com/v1/create-qr-code/', [
//         'data' => $qrCodeValue,
//         'size' => '200x200',
//         'format' => 'png',
//     ]);

//     // Check if the request was successful
//     if ($response->successful()) {
//         // Save the QR code image to storage
//         $qrCodeImagePath = "qrcodes/{$patient->id}.png";
//         Storage::put($qrCodeImagePath, $response->body());

//         // Return a response or redirect as needed
//         return redirect()->route('patients.details', $patient->id)->with('success', 'QR Code generated successfully.');
//     }

//     // Handle the case when the request fails
//     return redirect()->route('patients.details', $patient->id)->with('error', 'Failed to generate QR Code.');
// }


public function generateQrCode(Patient $patient)
{
    // Generate QR code value including patient ID
    $qrCodeValue = "{$patient->id}";

    // Make a request to an online QR code generator API
    $response = Http::get('https://api.qrserver.com/v1/create-qr-code/', [
        'data' => $qrCodeValue,
        'size' => '200x200',
        'format' => 'png',
    ]);

    // Check if the request was successful
    if ($response->successful()) {
        // Save the QR code image to storage
        $qrCodeImagePath = "{$patient->id}.png";
        Storage::put($qrCodeImagePath, $response->body());

        // Return a response or redirect as needed
        return redirect()->route('patients.details', $patient->id)->with('success', 'QR Code updated successfully.');
    }

    // Handle the case when the request fails
    return redirect()->route('patients.details', $patient->id)->with('error', 'Failed to update QR Code.');
}



public function generateDynamicQrCode(Request $request, Patient $patient)
{
    // Generate a unique identifier for the patient
    $identifier = md5($patient->id . $patient->updated_at);

    // Store the identifier on the patient record
    $patient->update(['qr_identifier' => $identifier]);

    // URL to a server endpoint that dynamically fetches the latest information
    $redirectUrl = route('patients.dynamicQrRedirect', ['identifier' => $identifier]);

    // Generate QR code value including patient ID and redirect URL
    $qrCodeValue = "Patient ID: {$patient->id}, Redirect URL: {$redirectUrl}";

    // Generate QR code
    $qrCode = QrCode::size(200)->generate($qrCodeValue);

    // Save the QR code image to storage
    $qrCodeImagePath = "qrcodes/{$patient->id}.png";
    Storage::put($qrCodeImagePath, $qrCode);

    // Redirect to the patient details page with success message
    return redirect()->route('patients.details', $patient->id)->with('success', 'QR Code updated successfully.');
}
public function dynamicQrRedirect(Request $request, $identifier)
{
    // Fetch patient information based on the identifier
    $patient = Patient::where('qr_identifier', $identifier)->first();

    if ($patient) {
        // Fetch prescriptions for the patient with associated drug names and end dates
        $prescriptions = $patient->prescriptions()->with('drug')->get();

        // Calculate remaining days for each drug
        $remainingDaysInfo = $prescriptions->map(function ($prescription) {
            $remainingDays = Carbon::now()->diffInDays(Carbon::parse($prescription->end_date));
            return "{$prescription->drug->drug_name}: {$remainingDays} days remaining";
        });

        // Display or use the information as needed
        $remainingDays = $remainingDaysInfo->implode(', ');

        return view('patients.dynamic_qr_redirect', [
            'patient' => $patient,
            'remainingDays' => $remainingDays,
        ]);
    }

    abort(404); // Or handle accordingly if patient not found
}


}

<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\DashboardController;
use App\Models\patient;
use App\Http\Controllers\MedicineUsageController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Display list of users
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Route to show the form to add a new user
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

// Route to handle user creation
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Route to show the form to edit a user
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

// Route to handle user updates
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

// Route to handle user deletion
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/', function () {
    return view('welcome'); // This returns the welcome.blade.php view
})->name('welcome'); // Name the route for easy access

Route::view('/about', 'about')->name('about');
Route::view('/help', 'help')->name('help');

Route::get('/medicine-usage-chart', [MedicineUsageController::class, 'showMedicineUsageChart']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/redirects', [LoginController::class, 'index'])->name('dashboard');
    Route::get("/setting",[LoginController::class,"setting"]);
    Route::put('/profile', [LoginController::class,"updatep"])->name('updatep');
    // Route::post('/changepassword', [AdminController::class,"updatepass"])->name('updatepass');
    Route::post("/changePassword/{id}",[LoginController::class,"changePassword"])->name('change-password');
});

//Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::match(['get', 'post'], '/patients/{patient}/generate-dynamic-qr', [PatientController::class, 'generateDynamicQrCode'])
    ->name('patients.generateDynamicQrCode');
Route::get('/patients/dynamic-qr-redirect/{identifier}', [PatientController::class, 'dynamicQrRedirect'])
    ->name('patients.dynamicQrRedirect');

Route::get('/redirects', [LoginController::class, 'index'])->middleware('auth');

// Display all clinics
Route::get('/clinics', [ClinicController::class, 'index'])->name('clinic.index');

// Show the form to create a new clinic
Route::get('/clinics/create', [ClinicController::class, 'create'])->name('clinic.create');

// Store a newly created clinic
Route::post('/clinics', [ClinicController::class, 'store'])->name('clinic.store');

// Show the form to edit a clinic
Route::get('/clinics/{clinic}/edit', [ClinicController::class, 'edit'])->name('clinic.edit');

// Update a clinic
Route::put('/clinics/{clinic}', [ClinicController::class, 'update'])->name('clinic.update');

// Delete a clinic
Route::delete('/clinics/{clinic}', [ClinicController::class, 'destroy'])->name('clinic.destroy');



// Index page
Route::get('/drugs', [DrugController::class, 'index'])->name('drugs.index');

// Show create form
Route::get('/drugs/create', [DrugController::class, 'create'])->name('drugs.create');

// Store drug
Route::post('/drugs', [DrugController::class, 'store'])->name('drugs.store');

// Show edit form
Route::get('/drugs/{id}/edit', [DrugController::class, 'edit'])->name('drugs.edit');

// Update drug
Route::put('/drugs/{id}', [DrugController::class, 'update'])->name('drugs.update');

// Delete drug
Route::delete('/drugs/{id}', [DrugController::class, 'destroy'])->name('drugs.destroy');

// Display a listing of patients
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

// Show the form for creating a new patient
Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

// Store a newly created patient in storage
Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

// Display the specified patient
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

// Show the form for editing the specified patient
Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');

// Update the specified patient in storage
Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');

// Remove the specified patient from storage
Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

// Display a listing of prescriptions
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');

// Show the form for creating a new prescription
Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');

// Store a newly created prescription in storage
Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');

// Display the specified prescription
Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

// Show the form for editing the specified prescription
Route::get('/prescriptions/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');

// Update the specified prescription in storage
Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');

// Remove the specified prescription from storage
Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
Route::get('/patients/{patient}/details', [PatientController::class, 'showDetails'])->name('patients.details');
Route::post('/patients/{patient}/generate-qrcode', [PatientController::class, 'generateQrCode'])->name('patients.generateQrCode');
Route::post('/patients/{patient}/save-qrcode', [PatientController::class, 'saveQrCode'])->name('patients.saveQrCode');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/medicine-usage-chart', [MedicineUsageController::class, 'showMedicineUsageChart']);

require __DIR__.'/auth.php';

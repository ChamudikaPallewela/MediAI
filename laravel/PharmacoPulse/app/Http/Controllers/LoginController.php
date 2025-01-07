<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\drugs;
use App\Models\patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check account type
            if ($user->user_type === 'pharmacy' || $user->user_type === 'Admin') {
                // Get counts for drugs, patients, and users
                $drugCount = drugs::count();
                $patientCount = patient::count();
                $userCount = User::where('user_type', 'Admin')->count();

                // Get chart data
                $medicineUsages = DB::table('medicine_usages')
                    ->join('drugs', 'medicine_usages.drug_id', '=', 'drugs.id')
                    ->selectRaw('month, COUNT(*) as count, drugs.drug_name')
                    ->groupBy('month', 'drugs.drug_name')
                    ->orderByRaw("CASE
                        WHEN month = 'January' THEN 1
                        WHEN month = 'February' THEN 2
                        WHEN month = 'March' THEN 3
                        WHEN month = 'April' THEN 4
                        WHEN month = 'May' THEN 5
                        WHEN month = 'June' THEN 6
                        WHEN month = 'July' THEN 7
                        WHEN month = 'August' THEN 8
                        WHEN month = 'September' THEN 9
                        WHEN month = 'October' THEN 10
                        WHEN month = 'November' THEN 11
                        WHEN month = 'December' THEN 12
                    END")
                    ->get();

                $chartData = [];

                foreach ($medicineUsages as $usage) {
                    $chartData[$usage->month][$usage->drug_name] = $usage->count;
                }

                // Pass counts and chart data to the view
                return view('index', compact('drugCount', 'patientCount', 'userCount', 'chartData'));
            } else {
                // Handle other account types or show an error message
                return redirect()->back()->with('error', 'Invalid account type');
            }
        } else {
            // Redirect to the login page if the user is not authenticated
            return redirect('/login');
        }
    }

    public function setting()
    {
        $user = Auth::user();
        return view("setting", compact("user"));
    }

    // Admin profile update
    public function updatep(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            // Add other profile fields validation here if needed
        ]);

        // Update the user's profile
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        // Update other profile fields here if needed
        $user->save();

        // Redirect the user back to the profile page with a success message
        return redirect()->back();
    }

    // Change password for admin
    public function changePassword(Request $request, $id)
    {
        $data = User::find($id);

        $data->update([
            'password' => Hash::make($request->newpw),
        ]);

        Session::flash('success', 'Password updated successfully!');
        Auth::logout();

        return redirect('/login');
    }
}

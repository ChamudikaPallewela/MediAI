<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Method to list users
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Method to show the form to create a new user
    public function create()
    {
        return view('users.create');
    }

    // Method to handle storing a new user
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'user_type' => 'required',
        ]);

        // Create the new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Method to show the form to edit an existing user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Method to handle updating a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required',
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
            // Update password only if provided
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    // Method to delete a user (already implemented)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}

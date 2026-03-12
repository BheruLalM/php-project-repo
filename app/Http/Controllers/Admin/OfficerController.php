<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = User::officers()->paginate(10);
        return view('admin.officers.index', compact('officers'));
    }

    public function create()
    {
        return view('admin.officers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'badge_number' => 'required|string|unique:users',
            'rank' => 'required|string',
            'station_branch' => 'required|string',
        ]);

        $officer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'badge_number' => $validated['badge_number'],
            'rank' => $validated['rank'],
            'station_branch' => $validated['station_branch'],
            'role' => 'officer',
        ]);

        $officer->assignRole('officer');

        return redirect()->route('admin.officers.index')->with('success', 'Officer created successfully.');
    }

    public function edit(string $id)
    {
        $officer = User::findOrFail($id);
        return view('admin.officers.edit', compact('officer'));
    }

    public function update(Request $request, string $id)
    {
        $officer = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$officer->id,
            'badge_number' => 'required|string|unique:users,badge_number,'.$officer->id,
            'rank' => 'required|string',
            'station_branch' => 'required|string',
        ]);

        $officer->update($validated);

        return redirect()->route('admin.officers.index')->with('success', 'Officer updated successfully.');
    }

    public function destroy(string $id)
    {
        $officer = User::findOrFail($id);
        // Deactivate instead of delete as per requirements
        $officer->update(['is_active' => false]);

        return redirect()->route('admin.officers.index')->with('success', 'Officer deactivated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\CrimeRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ComplaintController extends Controller
{
    public function index()
    {
        // Admin sees all, officers see assigned (or search all if privileged)
        $complaints = Complaint::with(['officer', 'caseRecord'])
            ->latest()
            ->paginate(15);

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        Gate::authorize('create', Complaint::class);
        $cases = CrimeRecord::all();
        $officers = User::officers()->get();
        return view('complaints.create', compact('cases', 'officers'));
    }

    public function store(StoreComplaintRequest $request)
    {
        Gate::authorize('create', Complaint::class);

        Complaint::create($request->validated());

        return redirect()->route(Auth::user()->role . '.complaints.index')->with('success', 'Complaint recorded.');
    }

    public function show(string $id)
    {
        $complaint = Complaint::with(['officer', 'caseRecord'])->findOrFail($id);
        Gate::authorize('view', $complaint);
        
        return view('complaints.show', compact('complaint'));
    }

    public function edit(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        Gate::authorize('update', $complaint);
        
        $cases = CrimeRecord::all();
        $officers = User::officers()->get();
        
        return view('complaints.edit', compact('complaint', 'cases', 'officers'));
    }

    public function update(StoreComplaintRequest $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);
        Gate::authorize('update', $complaint);

        $complaint->update($request->validated());

        return redirect()->route(Auth::user()->role . '.complaints.index')->with('success', 'Complaint updated.');
    }

    public function destroy(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        Gate::authorize('delete', $complaint);

        $complaint->delete();

        return redirect()->route('admin.complaints.index')->with('success', 'Complaint deleted.');
    }
}

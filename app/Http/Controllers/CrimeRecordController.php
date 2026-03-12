<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCrimeRecordRequest;
use App\Models\CrimeRecord;
use App\Traits\LogsActivity;
use App\Models\Criminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CrimeRecordController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $type = request('type');

        $cases = CrimeRecord::with(['criminal', 'officer'])
            ->when($search, fn($q) => $q->search($search))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($type, fn($q) => $q->where('crime_type', $type))
            ->latest()
            ->paginate(15);

        return view('cases.index', compact('cases'));
    }

    public function create()
    {
        Gate::authorize('create', CrimeRecord::class);
        $criminals = Criminal::all();
        $officers = User::officers()->get();
        return view('cases.create', compact('criminals', 'officers'));
    }

    public function store(StoreCrimeRecordRequest $request)
    {
        Gate::authorize('create', CrimeRecord::class);

        $caseNumber = 'CRMS-' . date('Y') . '-' . str_pad(CrimeRecord::count() + 1, 5, '0', STR_PAD_LEFT);

        $data = $request->validated();
        $data['case_number'] = $caseNumber;

        CrimeRecord::create($data);

        return redirect()->route(Auth::user()->role . '.cases.index')->with('success', "Case {$caseNumber} filed successfully.");
    }

    public function show(string $id)
    {
        $case = CrimeRecord::with(['criminal', 'officer', 'complaints', 'evidences.uploader'])->findOrFail($id);
        
        LogsActivity::createLog('viewed', $case);

        return view('cases.show', compact('case'));
    }

    public function edit(string $id)
    {
        $case = CrimeRecord::findOrFail($id);
        Gate::authorize('update', $case);
        
        $criminals = Criminal::all();
        $officers = User::officers()->get();
        
        return view('cases.edit', compact('case', 'criminals', 'officers'));
    }

    public function update(StoreCrimeRecordRequest $request, string $id)
    {
        $case = CrimeRecord::findOrFail($id);
        Gate::authorize('update', $case);

        $case->update($request->validated());

        return redirect()->route(Auth::user()->role . '.cases.index')->with('success', "Case {$case->case_number} updated.");
    }

    public function destroy(string $id)
    {
        $case = CrimeRecord::findOrFail($id);
        Gate::authorize('delete', $case);

        $case->delete();

        return redirect()->route('admin.cases.index')->with('success', 'Case record deleted.');
    }

    public function archive(string $id)
    {
        $case = CrimeRecord::findOrFail($id);
        
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $case->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('success', "Case {$case->case_number} has been archived.");
    }
}

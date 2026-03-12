<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCriminalRequest;
use App\Models\Criminal;
use App\Traits\LogsActivity;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CriminalController extends Controller
{
    protected $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $search = request('search');
        $criminals = Criminal::when($search, fn($q) => $q->search($search))->paginate(15);
        
        return view('criminals.index', compact('criminals'));
    }

    public function create()
    {
        Gate::authorize('create', Criminal::class);
        return view('criminals.create');
    }

    public function store(StoreCriminalRequest $request)
    {
        Gate::authorize('create', Criminal::class);

        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->fileService->uploadMugshot($request->file('photo'), $data['full_name']);
        }

        $criminal = Criminal::create($data);
        LogsActivity::createLog('created', $criminal);

        return redirect()->route(Auth::user()->role . '.criminals.index')->with('success', 'Criminal record created.');
    }

    public function show(string $id)
    {
        $criminal = Criminal::with('crimeRecords.officer')->findOrFail($id);
        
        LogsActivity::createLog('viewed', $criminal);

        return view('criminals.show', compact('criminal'));
    }

    public function edit(string $id)
    {
        $criminal = Criminal::findOrFail($id);
        Gate::authorize('update', $criminal);
        return view('criminals.edit', compact('criminal'));
    }

    public function update(StoreCriminalRequest $request, string $id)
    {
        $criminal = Criminal::findOrFail($id);
        Gate::authorize('update', $criminal);

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $this->fileService->deleteFile($criminal->photo_path);
            $data['photo_path'] = $this->fileService->uploadMugshot($request->file('photo'), $data['full_name']);
        }

        $criminal->update($data);
        LogsActivity::createLog('edited', $criminal);

        return redirect()->route(Auth::user()->role . '.criminals.index')->with('success', 'Criminal record updated.');
    }

    public function destroy(string $id)
    {
        $criminal = Criminal::findOrFail($id);
        Gate::authorize('delete', $criminal);

        LogsActivity::createLog('deleted', $criminal);

        $this->fileService->deleteFile($criminal->photo_path);
        $criminal->delete();

        return redirect()->route('admin.criminals.index')->with('success', 'Criminal record deleted.');
    }
}

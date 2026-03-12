<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvidenceRequest;
use App\Models\CrimeRecord;
use App\Models\Evidence;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EvidenceController extends Controller
{
    protected $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(StoreEvidenceRequest $request)
    {
        $case = CrimeRecord::findOrFail($request->crime_record_id);
        
        // Authorization: Admin or Assigned Officer
        if (Gate::denies('update', $case)) {
            abort(403, 'Unauthorized to upload evidence for this case.');
        }

        $path = $this->fileService->uploadEvidence($request->file('file'), $case->case_number);

        Evidence::create([
            'crime_record_id' => $case->id,
            'file_path' => $path,
            'file_type' => $request->file_type,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'description' => $request->description,
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Evidence uploaded and secured.');
    }

    public function destroy(string $id)
    {
        $evidence = Evidence::findOrFail($id);
        
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only admins can delete evidence.');
        }

        $this->fileService->deleteFile($evidence->file_path);
        $evidence->delete();

        return back()->with('success', 'Evidence record and file deleted.');
    }
}

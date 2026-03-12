<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a criminal mugshot to storage/app/public/mugshots/.
     *
     * @param  UploadedFile  $file         The uploaded image file.
     * @param  string        $criminalName Used to generate a readable filename.
     * @return string        The stored path (relative to disk root), e.g. mugshots/john-doe_abc123.jpg
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadMugshot(UploadedFile $file, string $criminalName): string
    {
        $this->validateFile($file, [
            'mimes'    => ['jpeg', 'jpg', 'png'],
            'max_size' => 2048,  // KB
        ]);

        $filename = Str::slug($criminalName) . '_' . uniqid() . '.' . $file->extension();

        Storage::disk('public')->putFileAs('mugshots', $file, $filename);

        return 'mugshots/' . $filename;
    }

    /**
     * Upload an evidence file (image or PDF) to storage/app/public/evidence/.
     *
     * @param  UploadedFile  $file        The uploaded file.
     * @param  string        $caseNumber  Used to generate a readable filename.
     * @return string        The stored path, e.g. evidence/crms-2024-00001_abc123.pdf
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadEvidence(UploadedFile $file, string $caseNumber): string
    {
        $this->validateFile($file, [
            'mimes'    => ['jpeg', 'jpg', 'png', 'pdf'],
            'max_size' => 5120,  // KB
        ]);

        $filename = Str::slug($caseNumber) . '_' . uniqid() . '.' . $file->extension();

        Storage::disk('public')->putFileAs('evidence', $file, $filename);

        return 'evidence/' . $filename;
    }

    /**
     * Delete a file from the public disk.
     *
     * @param  string  $path  Relative path (e.g. mugshots/filename.jpg)
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        if (! $path) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    // ─────────────────── Private helpers ───────────────────

    /**
     * Validate the uploaded file inline.
     * Throws ValidationException if validation fails.
     *
     * @param  UploadedFile  $file
     * @param  array         $rules  ['mimes' => [...], 'max_size' => int (KB)]
     */
    private function validateFile(UploadedFile $file, array $rules): void
    {
        $extension = strtolower($file->extension());

        if (! in_array($extension, $rules['mimes'])) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed: ' . implode(', ', $rules['mimes'])
            );
        }

        $sizeKb = $file->getSize() / 1024;

        if ($sizeKb > $rules['max_size']) {
            throw new \InvalidArgumentException(
                "File too large. Maximum allowed: {$rules['max_size']} KB."
            );
        }
    }
}

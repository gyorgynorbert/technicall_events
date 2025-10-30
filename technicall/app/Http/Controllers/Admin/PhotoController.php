<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;      // Added for logging
use Illuminate\Support\Facades\Storage;  // Added for file operations

class PhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'label' => 'nullable|string|max:255',
        ]);

        try {
            // 1. Store the file
            $path = $request->file('photo')->store('photos', 'public');

            // 2. Create the database record
            $student->photos()->create([
                'path' => $path,
                'label' => $validated['label'] ?? $request->file('photo')->getClientOriginalName(),
            ]);

            toast()->success('Photo uploaded successfully.')->push();
        } catch (\Exception $e) {
            Log::error("Photo upload failed for student {$student->id}: ".$e->getMessage());
            toast()->danger('Error uploading photo. Please try again.')->push();

            // If file was stored but DB failed, we should clean up.
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        try {
            // 1. Delete the file
            // We check if it exists first just to be safe
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }

            // 2. Delete the database record
            $photo->delete();

            toast()->success('Photo deleted successfully.')->push();
        } catch (\Exception $e) {
            Log::error("Photo deletion failed (ID: {$photo->id}): ".$e->getMessage());
            toast()->danger('Error deleting photo. Please try again.')->push();
        }

        return back();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Store a new photo for a student.
     */
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'label' => 'nullable|string|max:255',
        ]);

        $path = $request->file('photo')->store('photos', 'public');

        // 2. Create the database record
        $student->photos()->create([
            'path' => $path,
            'label' => $request->label ?? $request->file('photo')->getClientOriginalName(),
        ]);

        return back()->with('success', 'Photo uploaded successfully.');
    }

    /**
     * Delete a photo.
     */
    public function destroy(Photo $photo)
    {
        // 1. Delete the file from storage
        Storage::disk('public')->delete($photo->path);

        // 2. Delete the database record
        $photo->delete();

        return back()->with('success', 'Photo deleted successfully.');
    }
}

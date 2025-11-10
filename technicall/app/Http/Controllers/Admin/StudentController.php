<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest; // Added for create/edit forms
// CHANGE HERE
use App\Models\Grade;  // Import new Form Request
use App\Models\Student; // Import new Form Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for logging
use Illuminate\Support\Str; // Added for Str::random

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This query is already perfect.
        $students = Student::with('grade.school')->withCount('photos')->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Must pass grades (with their schools) to the form
        $grades = Grade::with('school')->orderBy('name')->get();

        return view('admin.students.create', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // CHANGE HERE
    public function store(StoreStudentRequest $request)
    {
        // CHANGE HERE
        // Validation is handled by StoreStudentRequest
        $validated = $request->validated();

        // Generate cryptographically secure 32-character access key
        do {
            $access_key = Str::random(32);
        } while (Student::where('access_key', $access_key)->exists());

        $validated['access_key'] = $access_key;

        try {
            Student::create($validated);
            toast()->success('Student created successfully.')->push();

            return redirect()->route('students.index');
        } catch (\Exception $e) {
            Log::error('Student creation failed: '.$e->getMessage());
            toast()->danger('Error creating student. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // This query is already perfect.
        $student->load('grade.school', 'photos', 'orders');

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Must pass grades (with their schools) to the form
        $grades = Grade::with('school')->orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    // CHANGE HERE
    public function update(UpdateStudentRequest $request, Student $student)
    {
        // CHANGE HERE
        // Validation is handled by UpdateStudentRequest
        $validated = $request->validated();

        try {
            // CHANGE HERE
            $student->update($validated);
            toast()->success('Student updated successfully.')->push();

            return redirect()->route('students.index');
        } catch (\Exception $e) {
            Log::error("Student update failed (ID: {$student->id}): ".$e->getMessage());
            toast()->danger('Error updating student. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Guard clause to prevent deleting a student with orders
        if ($student->orders()->exists()) {
            toast()->danger("Cannot delete '{$student->name}'. It is linked to existing orders.")->push();

            return redirect()->route('students.index');
        }

        // This is dangerous as it deletes photos AND orders (financial data).
        try {
            // --- Pre-deletion calculation of blast radius ---
            $studentName = $student->name;
            $photoCount = $student->photos()->count();
            // --- End calculation ---

            // This will cascade to photos
            $student->delete();

            // Provide specific, contextual feedback
            if ($photoCount > 0) {
                toast()->success("Student '{$studentName}' deleted. This also deleted {$photoCount} photos.")->push();
            } else {
                toast()->success("Student '{$studentName}' deleted successfully.")->push();
            }
        } catch (\Exception $e) {
            Log::error("Student deletion failed (ID: {$student->id}): ".$e->getMessage());
            toast()->danger('Error deleting student. Please try again.')->push();
        }

        return redirect()->route('students.index');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student; // Added for create/edit forms
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
        ]);

        // Fix for "ensure key is truly unique"
        do {
            $access_key = Str::random(16);
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
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
        ]);

        try {
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
        // This is dangerous as it deletes photos AND orders (financial data).
        try {
            // --- Pre-deletion calculation of blast radius ---
            $studentName = $student->name;
            $photoCount = $student->photos()->count();
            $orderCount = $student->orders()->count();
            // --- End calculation ---

            // This will cascade to photos and orders
            $student->delete();

            // Provide specific, contextual feedback
            if ($photoCount > 0 || $orderCount > 0) {
                toast()->success("Student '{$studentName}' deleted. This also deleted {$photoCount} photos and {$orderCount} orders.")->push();
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

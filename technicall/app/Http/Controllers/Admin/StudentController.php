<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('grade.school')->orderBy('name')->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Eager load schools to show "School Name - Grade Name"
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

        // Add the auto-generated access_key
        $validated['access_key'] = Str::random(16);

        // We'll add a loop here later to ensure the key is truly unique
        // For now, this is fine.

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Eager load the photos
        $student->load('photos');

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
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

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}

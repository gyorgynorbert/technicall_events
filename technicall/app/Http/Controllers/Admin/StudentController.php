<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('grade.school')->withCount('photos');

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $students = $query->paginate(10)->appends($request->query());

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
    public function store(StoreStudentRequest $request)
    {
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
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

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

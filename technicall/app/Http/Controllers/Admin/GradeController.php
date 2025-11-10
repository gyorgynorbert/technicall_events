<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGradeRequest;
use App\Http\Requests\Admin\UpdateGradeRequest; // Added for create/edit forms
// CHANGE HERE
use App\Models\Grade;  // Import new Form Request
use App\Models\School; // Import new Form Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for logging

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This query is already perfect.
        $grades = Grade::with('school')->withCount('students')->paginate(10);

        return view('admin.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Must pass schools to the form
        $schools = School::orderBy('name')->get();

        return view('admin.grades.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // CHANGE HERE
    public function store(StoreGradeRequest $request)
    {
        // CHANGE HERE
        // Validation is handled by StoreGradeRequest
        $validated = $request->validated();

        try {
            // CHANGE HERE
            Grade::create($validated);
            toast()->success('Grade created successfully.')->push();

            return redirect()->route('grades.index');
        } catch (\Exception $e) {
            Log::error('Grade creation failed: '.$e->getMessage());
            toast()->danger('Error creating grade. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        // Added this method for completeness
        $grade->load('school', 'students'); // Eager-load relations

        return view('admin.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        // Must pass schools to the form
        $schools = School::orderBy('name')->get();

        return view('admin.grades.edit', compact('grade', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    // CHANGE HERE
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        // CHANGE HERE
        // Validation is handled by UpdateGradeRequest
        $validated = $request->validated();

        try {
            // CHANGE HERE
            $grade->update($validated);
            toast()->success('Grade updated successfully.')->push();

            return redirect()->route('grades.index');
        } catch (\Exception $e) {
            Log::error("Grade update failed (ID: {$grade->id}): ".$e->getMessage());
            toast()->danger('Error updating grade. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        // Guard clause to prevent deleting a grade that is not empty
        if ($grade->students()->exists()) {
            toast()->danger("Cannot delete '{$grade->name}'. It still contains students.")->push();

            return redirect()->route('grades.index');
        }

        // This is still dangerous because of the Student -> Photos/Orders cascade.
        try {
            // --- Pre-deletion calculation of blast radius ---
            $gradeName = $grade->name;
            // --- End calculation ---

            // This will cascade to students, photos, and orders
            $grade->delete();

            // Provide specific, contextual feedback
            toast()->success("Grade '{$gradeName}' deleted successfully.")->push();
        } catch (\Exception $e) {
            Log::error("Grade deletion failed (ID: {$grade->id}): ".$e->getMessage());
            toast()->danger('Error deleting grade. Please try again.')->push();
        }

        return redirect()->route('grades.index');
    }
}

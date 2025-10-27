<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::with('school')->orderBy('name')->paginate(10);

        return view('admin.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::orderBy('name')->get();

        return view('admin.grades.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        Grade::create($validated);

        return redirect()->route('grades.index')->with('success', 'Grade created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $schools = School::orderBy('name')->get();

        return view('admin.grades.edit', compact('grade', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        $grade->update($validated);

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully.');
    }
}

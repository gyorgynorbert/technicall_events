<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::with('event')->orderBy('name')->paginate(10);

        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::orderBy('name')->get();

        return view('admin.schools.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        School::create($validated);

        return redirect()->route('schools.index')->with('success', 'School created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $events = Event::orderBy('name')->get();

        return view('admin.schools.edit', compact('school', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\School;     // Added for create/edit forms
use App\Models\Student;   // Added for destroy calculation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   // Added for transactions
use Illuminate\Support\Facades\Log;  // Added for logging

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Changed to with('events') and kept withCount('grades')
        $schools = School::with('events')->withCount('grades')->paginate(10);

        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pass all Events to the form
        $events = Event::orderBy('name')->get();

        return view('admin.schools.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'event_ids' => 'nullable|array', // Validate the array
            'event_ids.*' => 'exists:events,id', // Validate each ID
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. Create the School
                $school = School::create([
                    'name' => $validated['name'],
                    'location' => $validated['location'],
                ]);

                // 2. Attach the Events
                if (! empty($validated['event_ids'])) {
                    $school->events()->sync($validated['event_ids']);
                }
            });

            toast()->success('School created successfully.')->push();

            return redirect()->route('schools.index');
        } catch (\Exception $e) {
            Log::error('School creation failed: ', $e->getMessage());
            toast()->danger('Error creating school. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        // Eager load all relations for the show view
        $school->load('events', 'grades.students');

        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        // Load the school's current events
        $school->load('events');
        // Load all available events
        $events = Event::orderBy('name')->get();

        return view('admin.schools.edit', compact('school', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'event_ids' => 'nullable|array',
            'event_ids.*' => 'exists:events,id',
        ]);

        try {
            DB::transaction(function () use ($school, $validated) {
                // 1. Update the School
                $school->update([
                    'name' => $validated['name'],
                    'location' => $validated['location'],
                ]);

                // 2. Sync the Events
                $eventIds = $validated['event_ids'] ?? [];
                $school->events()->sync($eventIds);
            });

            toast()->success('School updated successfully.')->push();

            return redirect()->route('schools.index');
        } catch (\Exception $e) {
            Log::error("School update failed (ID: {$school->id}): ".$e->getMessage());
            toast()->danger('Error updating school. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // This is still dangerous because of the Grade -> Student cascade.
        // We MUST keep this check.
        try {
            // --- Pre-deletion calculation of blast radius ---
            $schoolName = $school->name;
            $gradeCount = $school->grades()->count();
            $gradeIds = $school->grades->pluck('id');
            $studentCount = Student::whereIn('grade_id', $gradeIds)->count();
            // --- End calculation ---

            // This will cascade to grades, students, photos, and orders
            $school->delete();

            // Provide specific, contextual feedback
            if ($gradeCount > 0 || $studentCount > 0) {
                toast()->success("School '{$schoolName}' deleted. This also deleted {$gradeCount} grades and {$studentCount} students.")->push();
            } else {
                toast()->success("School '{$schoolName}' deleted successfully.")->push();
            }
        } catch (\Exception $e) {
            Log::error("School deletion failed (ID: {$school->id}): ".$e->getMessage());
            toast()->danger('Error deleting school. Please try again.')->push();
        }

        return redirect()->route('schools.index');
    }
}

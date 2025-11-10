<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest; // Added for create/edit forms
// CHANGE HERE
use App\Models\Event;  // Import new Form Request
use App\Models\School; // Import new Form Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Added for transactions
use Illuminate\Support\Facades\Log; // Added for logging

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This still works perfectly with belongsToMany
        $events = Event::withCount('schools')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // We need to pass all schools to the form
        $schools = School::orderBy('name')->get();

        return view('admin.events.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // CHANGE HERE
    public function store(StoreEventRequest $request)
    {
        // CHANGE HERE
        // Validation is already handled by StoreEventRequest
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                // 1. Create the event
                $event = Event::create([
                    'name' => $validated['name'],
                    'event_date' => $validated['event_date'],
                    'description' => $validated['description'],
                ]);

                // 2. Attach the schools
                if (! empty($validated['school_ids'])) {
                    $event->schools()->sync($validated['school_ids']);
                }
            });

            toast()->success('Event created successfully.')->push();

            return redirect()->route('events.index');
        } catch (\Exception $e) {
            Log::error('Event creation failed: '.$e->getMessage());
            toast()->danger('Error creating event. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Eager load schools for the show view
        $event->load('schools');

        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        // We need all schools, and the event's currently selected schools
        $event->load('schools'); // Loads $event->schools relationship
        $schools = School::orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    // CHANGE HERE
    public function update(UpdateEventRequest $request, Event $event)
    {
        // CHANGE HERE
        // Validation is already handled by UpdateEventRequest
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($event, $validated) {
                // 1. Update the event
                $event->update([
                    'name' => $validated['name'],
                    'event_date' => $validated['event_date'],
                    'description' => $validated['description'],
                ]);

                // 2. Sync the schools
                // If school_ids is empty or null, sync([]) will detach all schools.
                // CHANGE HERE
                $schoolIds = $validated['school_ids'] ?? [];
                $event->schools()->sync($schoolIds);
            });

            toast()->success('Event updated successfully.')->push();

            return redirect()->route('events.index');
        } catch (\Exception $e) {
            Log::error("Event update failed (ID: {$event->id}): ".$e->getMessage());
            toast()->danger('Error updating event. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // This is now SAFE. Deleting the event will only delete
        // the links in the 'event_school' table, not the schools.
        try {
            $eventName = $event->name;
            $event->delete();

            // Simple, confident success message.
            toast()->success("Event '{$eventName}' deleted successfully.")->push();
        } catch (\Exception $e) {
            Log::error("Event deletion failed (ID: {$event->id}): ".$e->getMessage());
            toast()->danger('Error deleting event. Please try again.')->push();
        }

        return redirect()->route('events.index');
    }
}

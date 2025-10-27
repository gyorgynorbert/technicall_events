<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Usernotnull\Toast\Concerns\WireToast;

class EventController extends Controller
{
    use WireToast;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $event = new Event; // Empty instance for the form

        return view('admin.events.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        Event::create($validated);

        toast()->success('Event created successfully.')->push();

        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        $event->update($validated);

        toast()->success('Event updated successfully.')->push();

        return redirect()->route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // We'll add a check here later to prevent deleting an event that has schools

        $event->delete();

        toast()->success('Event deleted successfully.')->push();

        return redirect()->route('events.index');
    }
}

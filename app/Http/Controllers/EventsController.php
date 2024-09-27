<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Event
use App\Models\Event;
//  Topic
use App\Models\Topic;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::all(); // Fetch all events from the database
        return view('Event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $topics = Topic::all(); // Fetch all topics from the database
        return view('Event.create', compact('topics')); // Pass topics to the view
    }


    /**
     * Store a newly created resource in storage.
     */
   // Store the newly created event
   public function store(Request $request)
   {
       // Validate the request data
       $request->validate([
        'location' => 'required|string|max:255',
        'event_time' => 'required|date_format:H:i',
        'topic' => 'required|exists:topics,id',
        'event_date' => 'required|date',
        'charges' => 'required|numeric',
        'contact_number' => 'required|string|max:15', // Adjust the max length as needed
    ]);






       // Create the new event
       $event = Event::create([
           'location' => $request->location,
           'time' => $request->event_time,
           'topic' => $request->topic,
           'event_date' => $request->event_date,
        //    'chefs' => $request->chefs ? explode(',', $request->chefs) : [], // Convert to array if provided
        //    'recipes' => $request->recipes ? explode(',', $request->recipes) : [], // Convert to array if provided
           'charges' => $request->charges,
           'contact_number' => $request->contact_number,
       ]);

       return redirect()->route('Event.index')->with('success', 'Event created successfully!');
   }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id); // Retrieve the event by ID
        return view('Event.edit', compact('event')); // Pass the event to the view
    }

    /**
     * Update the specified resource in storage.
     */
   // Update the specified event in storage
   public function update(Request $request, $id)
   {
       // Validate the incoming request data
       $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'start_date' => 'required|date',
           'end_date' => 'required|date|after_or_equal:start_date', // Ensure end date is after or equal to start date
           'location' => 'required|string|max:255',
        //    'status' => 'required|in:upcoming,completed,canceled', // Ensure valid status
       ]);

       // Find the event by ID
       $event = Event::findOrFail($id);

       // Update event attributes
       $event->name = $request->name;
       $event->description = $request->description;
       $event->start_date = $request->start_date;
       $event->end_date = $request->end_date;
       $event->location = $request->location;
    //    $event->status = $request->status;

       // Save the updated event
       $event->save();

       // Redirect to the events list or show page with a success message
       return redirect()->route('Event.index')->with('success', 'Event updated successfully.');
   }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function fetchEvents(Request $request, $topic)
    {
        // Check API Key from request header
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            // Fetch ongoing and past events based on the specific topic
            $events = Event::where('topic', $topic)
                ->where(function ($query) {
                    $query->where('event_date', '>=', now()) // Ongoing events
                          ->orWhere('event_date', '<', now()); // Past events
                })
                ->with(['chefs', 'recipes']) // Assuming relationships are defined
                ->get();

            // Prepare the response data
            $responseData = $events->map(function ($event) {
                // Determine the status based on the event date
                $status = $event->event_date >= now() ? 'open' : 'closed';

                return [
                    'event_location' => $event->location,
                    'event_time' => $event->time,
                    'chefs' => $event->chefs, // Assuming chefs is a relation
                    'recipes' => $event->recipes, // Assuming recipes is a relation
                    'charges' => $event->charges,
                    'event_date' => $event->event_date->format('Y-m-d'), // Format date for readability
                    'contact_number' => $event->contact_number,
                    'status' => $status, // Include the status of the event
                ];
            });

            return response()->json(['events' => $responseData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}

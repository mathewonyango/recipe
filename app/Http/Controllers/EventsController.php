<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Event
use App\Models\Event;
//  Topic
use App\Models\Topic;
use App\Models\Recipe;

//Carbon
use Illuminate\Support\Carbon; // For handling time and token expiration

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::with('topic')->get(); // Fetch all events with their related topics
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

    // dd($request);
       // Validate the request data
       $request->validate([
        'name'=>'required',
        'location' => 'required|string|max:255',
        'event_time' => 'required',
        'topic' => 'required',
        'event_date' => 'required',
        'charges' => 'required|numeric',
        'contact_number' => 'required|string|max:15', // Adjust the max length as needed
    ]);
       // Create the new event
       $event = Event::create([
            'name'=>$request->name,
           'location' => $request->location,
           'time' => $request->event_time,
           'topic_id' => $request->topic,
           'day_of_event' => $request->event_date,
        //    'chefs' => $request->chefs ? explode(',', $request->chefs) : [], // Convert to array if provided
        //    'recipes' => $request->recipes ? explode(',', $request->recipes) : [], // Convert to array if provided
           'charges' => $request->charges,
           'contact_number' => $request->contact_number,
       ]);

       return redirect()->route('events.index')->with('success', 'Event created successfully!');
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
       return redirect()->route(route: 'events.index')->with('success', 'Event updated successfully.');
   }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getEventsByTopic($topicId)
    {
        // Fetch events that belong to the specified topic
        $events = Event::with([
            'topic',
            'recipes.chef',
            'recipes.comments'
        ])
        ->where('topic_id', $topicId) // Adjust the field name as per your database schema
        ->get();

        // Check if any events were found
        if ($events->isEmpty()) {
            return response()->json([
                'message' => 'No events found for this topic'
            ], 404);
        }

        // Return the events details
        return response()->json($events->map(function ($event) {
            return [
                'Event_name' => $event->name,
                'location' => $event->location,
                'topic' => $event->topic ? $event->topic->name : 'No Topic',
                'event_day' => $event->day_of_event,
                'time' => $event->time,
                'charges' => $event->charges,
                'contact_number' => $event->contact_number,
                'recipes' => $event->recipes->pluck('title'),
                'comments' => $event->comments,
                'chefs_who_participated' => $event->recipes->pluck('chef.name')->unique(),
            ];
        }));
    }

    public function getAllEvents()
{
    // Fetch all events along with their related chefs and recipes
    $events = Event::with([
        'topic',           // Fetch the topic related to the event
        'recipes.chef',    // Fetch the chef associated with each recipe
        'recipes.comments'  // Fetch the comments related to each recipe
    ])->get();

    // Separate active (future) and past events based on the event date (date-only comparison)
    $ongoingEvents = $events->filter(function ($event) {
        return Carbon::parse($event->event_date)->isFuture() || Carbon::parse($event->event_date)->isToday();
    });

    $pastEvents = $events->filter(function ($event) {
        return Carbon::parse($event->event_date)->isPast() && !Carbon::parse($event->event_date)->isToday();
    });

    // Return formatted events with headings
    return [
        'Active_events' => [
            'heading' => 'Active Events',
            'events' => $ongoingEvents->map(function ($event) {
                return [
                    'Event_name' => $event->name,
                    'location' => $event->location,
                    'topic' => $event->topic ? $event->topic->name : 'No Topic',  // Access topic name
                    'event_day' => $event->day_of_event,
                    'time' => $event->time,
                    'charges' => $event->charges,
                    'contact_number' => $event->contact_number,
                    'recipes' => $event->recipes->pluck('title'),  // Pluck titles from the loaded relationship
                    'comments' => $event->comments,
                    'chefs_who_will_participate' => $event->recipes->pluck('chef.name')->unique(),  // Collect chef names
                ];
            })
        ],

        'past_events' => [
            'heading' => 'Past Events',
            'events' => $pastEvents->map(function ($event) {
                return [
                    'Event_name' => $event->name,
                    'location' => $event->location,
                    'topic' => $event->topic ? $event->topic->name : 'No Topic',  // Access topic name
                    'event_day' => $event->day_of_event,
                    'time' => $event->time,
                    'charges' => $event->charges,
                    'contact_number' => $event->contact_number,
                    'recipes' => $event->recipes->pluck('title'),  // Pluck titles from the loaded relationship
                    'comments' => $event->comments,
                    'chefs_who_participated' => $event->recipes->pluck('chef.name')->unique(),  // Collect chef names
                ];
            })
        ],
    ];
}



    public function getEventById(Request $request)
    {
        // Fetch the event by ID along with its related chefs and recipes
        $event = Event::with([
            'topic',
            'recipes.chef',
            'recipes.comments'
        ])->find($request->event_id);

        // Check if the event exists
        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        // Return the event details
        return response()->json([
            'Event_name' => $event->name,
            'location' => $event->location,
            'topic' => $event->topic ? $event->topic->name : 'No Topic',
            'event_day' => $event->day_of_event,
            'time' => $event->time,
            'charges' => $event->charges,
            'contact_number' => $event->contact_number,
            'recipes' => $event->recipes->pluck('title'),
            'comments' => $event->comments,
            'chefs_who_participated' => $event->recipes->pluck('chef.name')->unique(),
        ]);
    }
}

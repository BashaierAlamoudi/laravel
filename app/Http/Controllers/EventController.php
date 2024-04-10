<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\event_model; // Import the Event model
use App\Models\Coordinator; // Import the Coordinator model

class EventController extends Controller
{
    
    /**
     * Fetch all events.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        // Fetch all events from the Event model
        $events = event_model::all();  // Fixed variable name and removed unnecessary foreach loop
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->title,
                'date' => $event->eventStart,
            ];
        }

        // Return the events as a response (you can customize this)
        return response()->json($formattedEvents);
    }
    public function index1()
    {
        // Fetch all events from the Event model
        $events = event_model::all();  // Fixed variable name and removed unnecessary foreach loop
        $formattedEvents =array();

        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->eventId,
                'title' => $event->title,
                'start' => $event->eventStart,
                'end'=>$event->eventEnd,
            ];
        }

        // Return the events as a response (you can customize this)
        return response()->json($formattedEvents);
    }
 // add  events to the Event model
    public function add(Request $request)
{
    $event = new event_model([
        'title' => $request['title'],
        'eventStart' => $request['EventStart'],
        'eventEnd' => $request['EventEnd'],
        'description' => $request['Description'],
    ]);
    // Save the new Event instance to the database
    $event->save();

    //  return a response to indicate success or failure
    return response()->json(['message' => 'Event instance saved successfully']);
}


public function delete($eventId)
{
    $event = event_model::find($eventId);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

    try {
        $event->delete();
        return response()->json(['message' => 'Event successfully deleted'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete event', 'error' => $e->getMessage()], 500);
    }
}



public function update($eventId,Request $request )
{
    $event = event_model::find($eventId);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

   
    $event->eventStart = $request->input('EventStart');
    $event->eventEnd = $request->input('EventEnd');
    try {
        $event->save();

        return response()->json([
            'message' => 'event updated successfully',
            'data' => $event
        ], 200);
    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Failed to update the event',
            'error' => $e->getMessage()
        ], 500);
    }
}

   }
    
    
   
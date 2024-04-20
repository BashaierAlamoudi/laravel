<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Seminars;
use App\Models\User;


class SeminarController extends Controller
{
    
    
    public function fetchStudentsData() {
        
    $seminars = Seminars::where('type', 'student')
        ->with(['user'])
        ->get();

    $formattedData = $seminars->map(function ($seminar) {
        return [
            'seminarId' => $seminar->seminarId,
            'loginId' => $seminar->user ? $seminar->user->loginId : 'unknown',
            'Name' => $seminar->Name,
            'Title' => $seminar->title,
            'Field' => $seminar->field,
            'Location' => $seminar->location,
            'Date' => $seminar->date,
            'Time' => $seminar->time,
        ];
    });

    return response()->json($formattedData);
}

    
    public function fetchData() {
        $seminars = Seminars::all();

        $formattedData = $seminars->map(function ($seminar) {
            return [
                'seminarId' => $seminar->seminarId,
                'Name' => $seminar->Name, // Access 'Name' property directly from $seminar
                'Title' => $seminar->title,
                'Field' => $seminar->field,
                'Location' => $seminar->location,
                'Date' => $seminar->date,
                'Time' => $seminar->time,
            ];
        });
        
        return response()->json($formattedData);}


        public function fetchUserData($userId) {
            // Check if the user is a student
            $seminars = Seminars::where('type', 'student')
        ->with(['user'])
        ->get();
        
        $formattedData = [];
        $user = User::where('loginId', $userId)->first();


                foreach ($seminars as $seminar) {
                    if ($seminar->userId == $user->id) {
                        $formattedData[] = [
                            'seminarId' => $seminar->seminarId,
                            'Name' => $seminar->Name,
                            'Title' => $seminar->title,
                            'Field' => $seminar->field,
                            'Location' => $seminar->location,
                            'Date' => $seminar->date,
                            'Time' => $seminar->time,
                        ];
                    }
                }
            
                return response()->json($formattedData);
            }
            
        
        
        
            

        


  
    
        public function update(Request $request, $SeminarId) {
        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Field' => 'required|string|max:255',
            'Date' => 'required|date',
            'Location'=> 'required',
            'Time'=> 'required',
            'Name'=>'required',
    
        ]);

        $seminar = Seminars::find($SeminarId);
        if(!$seminar){
            return response()->json('nothing');
        }
        $seminar->title = $request->input('Title');
        $seminar->field = $request->input('Field');
        $seminar->location = $request->input('Location');
        $seminar->date = $request->input('Date');
        $seminar->time = $request->input('Time');
        $seminar ->save();
        
        

        // Return a response indicating success*/
        return response()->json($seminar);

    }
    

    public function delete($seminarId)
    {
        $id = intval($seminarId);
        // Delete Comprehensive instance
        $data = Seminars::find($id);
        $data->delete();
   
    }
    public function add(Request $request)
    {
        //return response()->json($request);
        $validatedData = $request->validate([
            'loginId' => 'nullable', // Make loginId nullable
            'Title' => 'nullable|string|max:255',
            'Field' => 'nullable|string|max:255',
            'Date' => 'required|date',
            'Location' => 'required|string',
            'Time' => 'required',
            'Type' => 'required|string', // Assuming 'Type' is passed in the request
            'Name' =>'nullable|string',
        ]);
    
        // Fetch the user ID based on the provided loginId
        $userId = null;
        if ($validatedData['loginId']) {
            $user = User::where('loginId', $validatedData['loginId'])->first();
            if ($user) {
                $userId = $user->id;
                // If Name is not provided, set it to the user's full name
                $validatedData['Name'] =  ($user->firstName . ' ' . $user->lastName);
            }
        } 
    
        // Create a new seminar object
        $seminar = new Seminars([
            'userId' => $userId,
            'Name' => $validatedData['Name'],
            'title' => $validatedData['Title'],
            'field' => $validatedData['Field'],
            'date' => $validatedData['Date'],
            'time' => $validatedData['Time'],
            'location' => $validatedData['Location'],
            'type' => $validatedData['Type'],
        ]);
       // return response($seminar);
    
        // Save the seminar object to the database
        $seminar->save();
        
    }}
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Seminars;
use App\Models\User;
use App\Models\SeminarAttendance;
use App\Models\event_model; // Import the Event model
use App\Http\Controllers\EventController;



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
            'Type'=>$seminar->type,
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
                'Type'=>$seminar->type,
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
        if ($seminar->type=='public'){
            $title= $validatedData['Title'] . ' Seminar';
            $event = new event_model([
                'title' => $title,
                'eventStart' => $validatedData['Date'],
                'eventEnd' => $validatedData['Date'],
                'description' => $validatedData['Time'],
            ]);
            $eventController = new EventController();
            $eventController->addSeminar($event);

        }
        
        
    }


    public function fetchStudentAttendance($id){
        // Retrieve all seminar attendances for the user
        $seminarAttendances = SeminarAttendance::where('user_id', $id)->get();
    
        // Map each attendance record to format the data
        $formattedData = $seminarAttendances->map(function ($seminarAttendance) {
            return [
                'seminarId' => $seminarAttendance->seminarId,
                'title' => $seminarAttendance->title,
                'date' => $seminarAttendance->date,
                'certificateFile' => $seminarAttendance->certificate
            ];
        });
    
        return response()->json($formattedData);
    }
    
    public function updateSeminarAttendance(Request $request, $id)
    {
        $seminarAttendance = SeminarAttendance::find($id);

        if (!$seminarAttendance) {
            return response()->json(['message' => 'Seminar attendance not found'], 404);
        }    
     $seminarAttendance->title= $request->input('title');
     $seminarAttendance->date = $request->input('date');
        $seminar ->save();
       
        return response()->json($seminarAttendance);
    }
 
 public function deleteSeminarAttendance($seminarId)
    {
      
        $Seminar = seminarAttendance::find($seminarId);
        if (!$Seminar) {
            return response()->json(['message' => 'Seminar not found'], 404);
        }
    
        try {
            $Seminar->delete();
            return response()->json(['message' => 'Seminar successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Seminar', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function addSeminarAttendance(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'loginId' => 'required|exists:users,loginId',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'certificateFile' => 'nullable|file|mimes:pdf', // Validate the file is a PDF if provided
        ]);
    
        // Fetch the user based on loginId
        $user = User::where('loginId', $validatedData['loginId'])->first();
    
        // Process the certificate file if it is included
        if ($request->hasFile('certificateFile')) {
            $file = $request->file('certificateFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('certificates', $filename, 'public'); // Store in the certificates directory
            $certificatePath = Storage::url($path);
        } else {
            $certificatePath = null; // No certificate provided
        }
    
        // Create the seminar attendance record
        $seminarAttendance = new SeminarAttendance([
            'user_id' => $user->id,  // Assuming your SeminarAttendance model uses 'user_id' as a foreign key
            'title' => $validatedData['title'],
            'date' => $validatedData['date'],
            'certificate' => $certificatePath,
        ]);
    
        // Save the seminar attendance to the database
        $seminarAttendance->save();
    
        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Seminar attendance added successfully',
            'data' => $seminarAttendance
        ], 201);
    }
    public function getPdf($filename)
{
    $path = storage_path('app/public/pdf/' . $filename); // Adjust the path as needed

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
}



}
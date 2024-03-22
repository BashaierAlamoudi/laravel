<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Seminars;
use App\Models\User;


class SeminarController extends Controller
{public function fetchData() {
    $seminars = Seminars::with(['user'])->get(); // Retrieve all seminars with user relationship loaded

    $formattedData = $seminars->map(function ($seminar) {
    $studentName = $seminar->user ? $seminar->user->firstName . ' ' . $seminar->user->lastName : 'Unknown';
    return [
            'seminarId' => $seminar->seminarId,
            'loginId' => $seminar->user?$seminar ->user->loginId:'unkown', // Access loginId through accessor method
            'StudentName' =>$seminar->studentName,
            'Title' => $seminar->title,
            'Field' => $seminar->field,
            'Location' => $seminar->location,
            'Date' => $seminar->date,
            'Time' => $seminar->time,
        ];
    });
    return response()->json($formattedData);
}


  
    public function update(Request $request, $SeminarId) {
        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Field' => 'required|string|max:255',
            'Date' => 'required|date',
            'Location'=> 'required',
            'Time'=> 'required',
    
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
            'loginId' => 'required|exists:user,loginId',
            'Title' => 'required|string|max:255',
            'Field' => 'required|string|max:255',
            'Date' => 'required|date',
            'Location'=>'required|string',
            'Time'=>'required'
        ]);
       // return response()->json($validatedData);
        $user = User::where('loginId', $validatedData['loginId'])->first();
       // if ($user){
            $seminar = new Seminars([
                'userId'=>$user->id,
                'title'=>$validatedData['Title'],
                'field'=>$validatedData['Field'],
                'date'=>$validatedData['Date'],
                'time'=>$validatedData['Time'],
                'location'=>$validatedData['Location'],
            ]);
            $seminar->save();
            return response()->json(['message' => 'seminar added successfully', 'data' => $seminar], 201);
       // } else {
           // return response()->json(['message' => 'PDF file is required'], 422);
       // }
        
    }}
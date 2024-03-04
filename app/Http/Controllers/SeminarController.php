<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Seminars;

class SeminarController extends Controller
{
  public function fetchData() {

        $data = Seminars::all(); // Example: Retrieve all records
        $formattedData = $data->map(function ($seminars) {
       
        return [
            
            'StudentId' => $seminars->studentId,
            'StudentName' => $seminars->studentName,
            'Title' => $seminars->title,
            'Field' => $seminars->field,
            'Location' => $seminars->location,
            'Date' =>$seminars->date,
            'Time' =>$seminars->time,
            // Add other attributes as needed
        ];
    });
    return response()->json($formattedData);

        
    }

  
    public function update(Request $request, $id) {

        $data = Seminars::find($id);
        $data->studentName = $request->input('StudentName');
        $data->title = $request->input('Title');
        $data->field = $request->input('Field');
        $data->location = $request->input('Location');
        $data->date = $request->input('Date');
        $data->time = $request->input('Time');
        $data ->save();
        
        

        // Return a response indicating success*/
        return response()->json($data);

    }
    

    public function delete($id)
    {
        $id = intval($id);
        // Delete Comprehensive instance
        $data = Seminars::find($id);
        $data->delete();
   
    }

    public function add(Request $request)
    {
        $seminars = new Seminars();
        $seminars->studentId = $request->input('StudentId');
        $seminars->studentName = $request->input('StudentName');
        $seminars->title = $request->input('Title');
        $seminars->field = $request->input('Field');
        $seminars->location = $request->input('Location');
        $seminars->date = $request->input('Date');
        $seminars->time = $request->input('Time');
        // Save the new Comp instance to the database
        $seminars->save();
        
       
    }
}

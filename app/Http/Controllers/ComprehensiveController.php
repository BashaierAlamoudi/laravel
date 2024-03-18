<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comperhensive_Exam;

class ComprehensiveController extends Controller
{//Request $request
    public function fetchData() {
  
        // Use your Eloquent model to retrieve data from the database

        $data = Comperhensive_Exam::all(); // Example: Retrieve all records
        $formattedData = $data->map(function ($comprehensive) {
        return [
            'ID' => $comprehensive->id,
            'StudentName' => $comprehensive->studentName,
            'Attempt' => $comprehensive->numAttempts,
            'Exam' => $comprehensive->examName,
            'Score' =>$comprehensive->score,
            'Date' =>$comprehensive->date,
            // Add other attributes as needed
        ];
    });
    return response()->json($formattedData);

        
    }

  

  
    public function update(Request $request, $id) {
        // Find the data by ID
        $data = Comp::find($id);

        $data->studentName = $request->input('StudentName');
        $data->score = $request->input('Score');
        $data->examName = $request->input('Exam');
        $data->numAttempts = $request->input('Attempt');
        $data->date = $request->input('Date');
        $data ->save();
        // Return a response indicating success
        return response()->json($data);

    }
    

    public function delete($id)
    {
        $id = intval($id);
        // Delete Comprehensive instance
        $data = Comp::find($id);
        $data->delete();
    }

    public function add(Request $request)
    {
        $comp = new Comp();
        $comp->id = $request->input('ID');
        $comp->studentName = $request->input('StudentName');
        $comp->numAttempts = $request->input('Attempt');
        $comp->examName = $request->input('Exam');
        $comp->score = $request->input('Score');
        $comp->date = $request->input('Date');

        // Save the new Comp instance to the database
        $comp->save();
        
       
    }
}

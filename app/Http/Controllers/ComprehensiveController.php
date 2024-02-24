<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comp;

class ComprehensiveController extends Controller
{//Request $request
    public function fetchData() {
  
        // Use your Eloquent model to retrieve data from the database

        $data = Comp::all(); // Example: Retrieve all records
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

    public function index()
    {
        $comprehensive = comp::all();
        return view('comprehensives.index', compact('comprehensives'));
    }

    public function create()
    {
        return view('comprehensives.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'studentName' => 'required',
            'numAttempts' => 'required|integer',
            'examName' => 'required',
            'score' => 'required|integer',
            'date' => 'required|date',
        ]);

        // Create new Comprehensive instance
        Comprehensive::create($request->all());

        return redirect()->route('comprehensives.index')
            ->with('success', 'Comprehensive created successfully.');
    }

    public function show(Comprehensive $comprehensive)
    {
        return view('comprehensives.show', compact('comprehensive'));
    }

    public function edit(Comprehensive $comprehensive)
    {
        return view('comprehensives.edit', compact('comprehensive'));
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
    //public function delete
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

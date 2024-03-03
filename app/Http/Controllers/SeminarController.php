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
            // Add other attributes as needed
        ];
    });
    return response()->json($formattedData);

        
    }

    public function index()
    {
        $seminars = Seminars::all();
        return view('seminars.index', compact('seminars'));
    }

    public function create()
    {
        return view('seminars.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'studentId' => 'required',
            'studentName' => 'required',
            'title' => 'required',
            'field' => 'required',
            'location' => 'required',
            'date' => 'required|date',
        ]);

        // Create new Comprehensive instance
        Seminars::create($request->all());

        return redirect()->route('seminars.index')
            ->with('success', 'seminars created successfully.');
    }

    public function show(Seminars $seminars)
    {
        return view('seminars.show', compact('seminars'));
    }

    public function edit(Seminars $seminars)
    {
        return view('seminars.edit', compact('seminars'));
    }

  
    public function update(Request $request, $id) {
        // Find the data by ID
        $data = Seminars::find($id);
        $data->studentId = $request->input('StudentId');
        $data->studentName = $request->input('StudentName');
        $data->title = $request->input('Title');
        $data->field = $request->input('Field');
        $data->location = $request->input('Location');
        $data->date = $request->input('Date');
        $data ->save();
        // Return a response indicating success
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

        // Save the new Comp instance to the database
        $seminars->save();
        
       
    }
}

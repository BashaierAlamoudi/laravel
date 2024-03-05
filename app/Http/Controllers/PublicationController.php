<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Publications;
class PublicationController extends Controller

{
    public function fetchData() {

        $data = Publications::all(); // Example: Retrieve all records
        $formattedData = $data->map(function ($publications) {
       
        return [
            
            'StudentId' => $publications->studentId,
            'StudentName' => $publications->studentName,
            'SupervisorName' => $publications->supervisorName,
            'Title' => $publications->title,
            'Field' => $publications->field,
            'Date' =>$publications->date,
            'PdfPath' =>$publications->pdfPath,
        ];
    });
    return response()->json($formattedData);

        
    }

    public function add(Request $request)
    {
        // Validate the incoming request data, including the PDF file
        $validatedData = $request->validate([
            'StudentId' => 'required|string|max:255',
            'StudentName' => 'required|string|max:255',
            'SupervisorName' => 'required|string|max:255',
            'Title' => 'required|string|max:255',
            'Field' => 'required|string|max:255',
            'Date' => 'required|date',
            'pdfFile' => 'required|file|mimes:pdf', // Validate the file is a PDF
        ]);
    
        if ($request->hasFile('pdfFile')) {
            $pdfFile = $request->file('pdfFile');
            $filename = time() . '_' . $pdfFile->getClientOriginalName();
            $path = $pdfFile->storeAs('pdf', $filename, 'public'); // Specifying 'public' as the disk
            $publication = new Publications();
            // Set other publication details from the validated data
            $publication->studentId = $validatedData['StudentId'];
            $publication->studentName = $validatedData['StudentName'];
            $publication->supervisorName = $validatedData['SupervisorName'];
            $publication->title = $validatedData['Title'];
            $publication->field = $validatedData['Field'];
            $publication->date = $validatedData['Date'];
            $publication->pdfPath = Storage::url($path); // Saving the file path as 'pdfPath'
            $publication->save();
    
            return response()->json(['message' => 'Publication added successfully', 'data' => $publication], 201);
        } else {
            return response()->json(['message' => 'PDF file is required'], 422);
        }
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

  
public function update(Request $request, $id)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'StudentName' => 'required',
        'SupervisorName' => 'required',
        'Title' => 'required',
        'Field' => 'required',
        'Date' => 'required|date',

    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Find the publication by ID
    $publication = Publications::find($id);

    // Check if the publication exists
    if (!$publication) {
        return response()->json(['message' => 'Publication not found'], 404);
    }

    // Update the publication with validated data
    $publication->studentName = $request->StudentName;
    $publication->supervisorName = $request->SupervisorName;
    $publication->title = $request->Title;
    $publication->field = $request->Field;
    $publication->date = $request->Date;
    // Include any additional fields here

    // Save the updated publication
    $publication->save();

    // Return the updated publication data or a success message
    return response()->json([
        'message' => 'Publication updated successfully',
        'data' => $publication
    ], 200);
}


public function delete($id)
{
    // Cast the id to an integer or use appropriate validation
    $id = intval($id);

    // Attempt to find the publication by ID
    $publication = Publications::find($id);

    // Check if the publication exists
    if (!$publication) {
        return response()->json(['message' => 'Publication not found'], 404);
    }

    // Delete the publication
    $publication->delete();

    // Return a success message
    return response()->json(['message' => 'Publication successfully deleted'], 200);
}
}



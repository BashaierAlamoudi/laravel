<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Publications;
use App\Models\User;
class PublicationController extends Controller

{
    public function fetchData() {
        
        $publications = Publications::with(['user', 'supervisors'])->get();

    // Format the fetched data
    $formattedData = $publications->map(function ($publication) {
        // Get student name from user relation
        $studentName = $publication->user ? $publication->user->firstName . ' ' . $publication->user->lastName : 'Unknown';
        
        // Get supervisor name from supervisors relation
        $supervisorName = $publication->supervisors ? $publication->supervisors->first()->firstName . ' ' . $publication->supervisors->first()->lastName : 'Unknown';

        return [
            'publicationId' => $publication->publicationId,
            'loginId' => $publication->user ? $publication->user->loginId : 'Unknown',
            'Title' => $publication->title,
            'Field' => $publication->field,
            'PublicationType' => $publication->publicationType,
            'VenueName' => $publication->venueName,
            'DOI' => $publication->doi,
            'Date' => $publication->date, // Ensure the date format is correct
            'PdfPath' => $publication->pdfPath,
            'StudentName' => $studentName,
            'SupervisorName' => $supervisorName,
        ];
    });

    // Return the formatted data as JSON response
    return response()->json($formattedData);
    }

    public function fetchStudentData($loginId) {
        // Filter publications where the associated user's loginId matches the provided ID
        $publications = Publications::with(['user', 'supervisors'])
                                    ->whereHas('user', function($query) use ($loginId) {
                                        $query->where('loginId', $loginId);
                                    })
                                    ->get();
    
        // Format the fetched data
        $formattedData = $publications->map(function ($publication) {
            $studentName = $publication->user ? $publication->user->firstName . ' ' . $publication->user->lastName : 'Unknown';
            $supervisorName = $publication->supervisors ? $publication->supervisors->first()->firstName . ' ' . $publication->supervisors->first()->lastName : 'Unknown';
    
            return [
                'publicationId' => $publication->publicationId,
                'loginId' => $publication->user ? $publication->user->loginId : 'Unknown',
                'Title' => $publication->title,
                'Field' => $publication->field,
                'PublicationType' => $publication->publicationType,
                'VenueName' => $publication->venueName,
                'DOI' => $publication->doi,
                'Date' => $publication->date,
                'PdfPath' => $publication->pdfPath,
                'StudentName' => $studentName,
                'SupervisorName' => $supervisorName,
            ];
        });
    
        // Return the formatted data as JSON response
        return response()->json($formattedData);
    }
    

    public function add(Request $request)
    {
        // Validate the incoming request data, including the PDF file 
        $validatedData = $request->validate([
            'loginId' => 'required|exists:user,loginId',
            'title' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'publicationType' => 'required|string|max:255',
            'venueName' => 'required|string|max:255',
            'doi' => 'required|string|max:255',
            'date' => 'required|date',
            'pdfFile' => 'required|file|mimes:pdf', // Validate the file is a PDF
        ]);
        $user = User::where('loginId', $validatedData['loginId'])->first();
    
        if ($request->hasFile('pdfFile')) {
            $pdfFile = $request->file('pdfFile');
            $filename = time() . '_' . $pdfFile->getClientOriginalName();
            $path = $pdfFile->storeAs('pdf', $filename, 'public'); // Specifying 'public' as the disk
            $publication = new Publications([
                'userId' => $user->id,
                'title' => $validatedData['title'],
                'field' => $validatedData['field'],
                'publicationType' => $validatedData['publicationType'],
                'venueName' => $validatedData['venueName'],
                'doi' => $validatedData['doi'],
                'date' => $validatedData['date'],
                'pdfPath' => Storage::url($path),
            ]); // Saving the file path as 'pdfPath'
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

  
public function update(Request $request, $publicationId)
{

    $validated = $request->validate([
        'Title' => 'required|string|max:255',
        'Field' => 'required|string|max:255',
        'PublicationType' => 'required|string|max:255',
        'VenueName' => 'required|string|max:255',
        'DOI' => 'required|string|max:255',
        'Date' => 'required|date',

    ]);


    $publication = Publications::find($publicationId);


    if (!$publication) {
        return response()->json(['message' => 'Publication not found'], 404);
    }


    $publication->title = $validated['Title'];
    $publication->field = $validated['Field'];
    $publication->publicationType = $validated['PublicationType'];
    $publication->venueName = $validated['VenueName'];
    $publication->doi = $validated['DOI'];
    $publication->date = $validated['Date'];



    try {
        $publication->save();

        return response()->json([
            'message' => 'Publication updated successfully',
            'data' => $publication
        ], 200);
    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Failed to update the publication',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function delete($publicationId)
{
    $publication = Publications::find($publicationId);

    if (!$publication) {
        return response()->json(['message' => 'Publication not found'], 404);
    }

    try {
        $publication->delete();
        return response()->json(['message' => 'Publication successfully deleted'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete publication', 'error' => $e->getMessage()], 500);
    }
}
}



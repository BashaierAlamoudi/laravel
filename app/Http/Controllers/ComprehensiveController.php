<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comprehensive_Exam;
use App\Models\Taken_Exam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ComprehensiveController extends Controller
{//Request $request

    public function fetchData()
{
    $data = Taken_Exam::with(['user', 'comprehensiveExam'])
                ->get()
                ->map(function ($takenExam) {
                    return [
                        'TakenExamId'=> $takenExam->takenExamId,
                        'loginId' => $takenExam->user->loginId,
                        'StudentName' => $takenExam->user->firstName . ' ' . $takenExam->user->lastName,
                        'Year' => $takenExam->comprehensiveExam->year,
                        'Season' => $takenExam->comprehensiveExam->season,
                        'OralScore' => $takenExam->oralScore,
                        'WrittenScore' => $takenExam->writtenScore,
                        'PdfPath' => $takenExam->comprehensiveExam->pdfPath,
                    ];
                });

    return response()->json($data);
}


  

  
public function update(Request $request, $TakenExamId)
{

    $validated = $request->validate([
        'WrittenScore' => 'nullable|integer',
        'OralScore' => 'nullable|integer',

    ]);


    $TakenExam = Taken_Exam::find($TakenExamId);


    if (!$TakenExam) {
        return response()->json(['message' => 'Taken Exam not found'], 404);
    }


    $TakenExam->writtenScore = $validated['WrittenScore'];
    $TakenExam->oralScore = $validated['OralScore'];



    try {
        $TakenExam->save();

        return response()->json([
            'message' => 'TakenExam updated ',
            'data' => $TakenExam
        ], 200);
    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Failed to update the TakenExam',
            'error' => $e->getMessage()
        ], 500);
    }
}
    

    public function delete($TakenExamId)
    {
        $TakenExam = Taken_Exam::find($TakenExamId);
    
        if (!$TakenExam) {
            return response()->json(['message' => 'TakenExam not found '. $TakenExamId], 404);
        }
    
        try {
            $TakenExam->delete();
            return response()->json(['message' => 'TakenExam successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete TakenExam', 'error' => $e->getMessage()], 500);
        }
    }

        public function add(Request $request)
        {
            // Validate the incoming request data
            $validated = $request->validate([
                'year' => 'required|string',
                'season' => 'required|string',
                'description' => 'required|string',
                'pdfFile' => 'required|file|mimes:pdf',
                'studentIds' => 'required|array',
                'studentIds.*' => 'required|exists:user,loginId', 
            ]);
    
            // Handle the PDF file upload
            if ($request->hasFile('pdfFile')) {
                $pdfFile = $request->file('pdfFile');
                $filename = time() . '_' . $pdfFile->getClientOriginalName();
                $path = $pdfFile->storeAs('pdf', $filename, 'public'); 
            }
    
            // Create the comprehensive exam entry
            $exam = Comprehensive_Exam::create([
                'year' => $validated['year'],
                'season' => $validated['season'],
                'description' => $validated['description'],
                'pdfPath' => Storage::url($path),
            ]);

            foreach ($validated['studentIds'] as $loginId) {
                $user = User::where('loginId', $loginId)->first();
    
                if ($user) {
                    Taken_Exam::create([
                        'loginId' => $user->id,
                        'examId' => $exam->examId,
                    ]);
                }
            }
    
            // Return a response indicating success
            return response()->json([
                'message' => 'Comprehensive exam and student entries added successfully',
                'examId' => $exam->id,
            ], 201); // HTTP 201 Created
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
    public function getStudentsByYearAndSeason(Request $request) {
        $validated = $request->validate([
            'year' => 'required|string',
            'season' => 'required|string',
        ]);
    
        // Fetching the exam to include its ID in the response
        $exam = DB::table('comprehensive_exam')
                  ->where('year', $validated['year'])
                  ->where('season', $validated['season'])
                  ->first();
    
        // Ensure that an exam was found
        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }
    
        $students = DB::table('comprehensive_exam')
                      ->join('taken_exam', 'comprehensive_exam.examId', '=', 'taken_exam.examId')
                      ->join('user', 'taken_exam.loginId', '=', 'user.id')
                      ->where('comprehensive_exam.year', $validated['year'])
                      ->where('comprehensive_exam.season', $validated['season'])
                      ->select('user.id as userId','user.loginId', DB::raw("CONCAT(user.firstName, ' ', user.lastName) as name"), 'taken_exam.writtenScore', 'taken_exam.oralScore', 'comprehensive_exam.examId')
                      ->get();
    
        return response()->json(['students' => $students, 'examId' => $exam->examId]);
    }
    
    public function assignGrades(Request $request) {
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.userId' => 'required|exists:user,id',
            'grades.*.loginId' => 'required|exists:user,loginId', // Assuming 'loginId' is a column in 'user'
            'grades.*.examId' => 'required|exists:comprehensive_exam,examId',
            'grades.*.writtenScore' => 'nullable|numeric',
            'grades.*.oralScore' => 'nullable|numeric',
        ]);
    
        foreach ($validated['grades'] as $grade) {
           
                // Now use $userId to update the record in 'taken_exam'
                DB::table('taken_exam')
                    ->where('loginId',$grade['userId']) // 'loginId' in 'taken_exam' matches 'id' in 'user'
                    ->where('examId', $grade['examId'])
                    ->update([
                        'writtenScore' => $grade['writtenScore'],
                        'oralScore' => $grade['oralScore'],
                    ]);
        }
    
        return response()->json(['message' => 'Grades successfully assigned']);
    }
    
}

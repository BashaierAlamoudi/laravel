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
use Illuminate\Support\Facades\Mail;
use App\Mail\ExamNotification;
use App\Mail\Test;
use App\Mail\PassExam;


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
                        'ExamName'=> $takenExam->comprehensiveExam->examName,
                        'Year' => $takenExam->comprehensiveExam->year,
                        'Season' => $takenExam->comprehensiveExam->season,
                        'OralScore' => $takenExam->oralScore,
                        'WrittenScore' => $takenExam->writtenScore,
                       
                    ];
                });

    return response()->json($data);
}

public function addNewExam(Request $request)
{
    $validated = $request->validate([
        'examName' => 'required|string|max:255',
        'year' => 'required|string|max:255',
        'season' => 'required|string|max:255'
    ]);

    $exam = Comprehensive_Exam::create($validated);

    return response()->json(['message' => 'New exam added successfully', 'examId' => $exam->id], 201);
}
  

  
public function update(Request $request, $TakenExamId)
{

    $validated = $request->validate([
        'WrittenScore' => 'nullable|numeric',
        'OralScore' => 'nullable|numeric',

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
public function index()
{
    $exams = Comprehensive_Exam::all(['examId as id', 'examName as name']);  // Assuming 'name' is the field for exam names
    return response()->json($exams);
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


        
        public function assignStudentsToExam(Request $request)
        {

                $request->validate([
                    'examId' => 'required|exists:comprehensive_exam,examId',
                    'studentIds' => 'required|array',
                    'studentIds.*' => 'required|exists:user,loginId'
                ]);
            
                // Process the data
                $examId = $request->input('examId');
                $studentIds = $request->input('studentIds');
            
                try {
                    foreach ($studentIds as $loginId) {
                        $user = User::where('loginId', $loginId)->firstOrFail();
                        Taken_Exam::create([
                            'userId' => $user->id, // assuming User model's id is what should be linked
                            'examId' => $examId,
                            'oralScore' => null,  // Assuming scores are not assigned yet
                            'writtenScore' => null
                        ]);}
                    return response()->json(['message' => 'Students successfully assigned to exam']);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 400);
                }
            }

            public function notifyExam(Request $request)
            {
                $validated = $request->validate([
                    'examId' => 'required|exists:comprehensive_exam,examId',
                    'examType' => 'required|in:Written,Oral',
                    'description' => 'required|string',
                    'subject' => 'required|string',
                    'pdfFile' => 'required|file|mimes:pdf'
                ]);
            
                // Handle the PDF file upload
                if ($request->hasFile('pdfFile')) {
                    $pdfFile = $request->file('pdfFile');
                    $filename = time() . '_' . $pdfFile->getClientOriginalName();
                    $path = $pdfFile->storeAs('pdf', $filename, 'public'); 
                }
                
                try {
                    $exam = Comprehensive_Exam::findOrFail($validated['examId']);
            
                    if ($validated['examType'] === 'Written') {
                        $exam->written_description = $validated['description'];
                        $exam->written_pdfPath = Storage::url($path);
                        // Fetch all users who are assigned to this written exam
                        $users = Taken_Exam::where('examId', $exam->examId)->with('user')->get()->pluck('user')->filter();
                    } elseif ($validated['examType'] === 'Oral') {
                        $exam->oral_description = $validated['description'];
                        $exam->oral_pdfPath = Storage::url($path);
                        $users = Taken_Exam::where('examId', $exam->examId)
                                           ->where('writtenScore', '>=', 60)
                                           ->with('user')
                                           ->get()
                                           ->pluck('user')
                                           ->filter(function ($user) {
                                               return !is_null($user);
                                           });
                    }
            
                    $exam->save();
                    
                    // Data to be sent in the email
                    $data = [
                        'subject' => $validated['subject'],
                        'description' => $validated['description'],
                        'path' => $path,
                    ];
            
                    // Send an email to each user
                    foreach ($users as $user) {
                        Mail::to($user->email)->send(new ExamNotification($data));
                    }
            
                    return response()->json(['message' => 'Exam notification updated successfully.'], 200);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
            // In ComprehensiveController.php

public function getStudentsByExamId($examId)
{
    $students = Taken_Exam::where('examId', $examId)
                          ->with('user') // assuming there's a relationship defined in Taken_Exam model
                          ->get()
                          ->map(function ($takenExam) {
                              return [
                                  'userId' => $takenExam->user->id,
                                  'loginId' => $takenExam->user->loginId,
                                  'name' => $takenExam->user->firstName . ' ' . $takenExam->user->lastName
                              ];
                          });

    if($students->isEmpty()) {
        return response()->json(['message' => 'No students found for this exam'], 404);
    }

    return response()->json($students);
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
    try {
        $validated = $request->validate([
            'examId' => 'required|exists:comprehensive_exam,examId',
            'grades' => 'required|array',
            'grades.*.studentId' => 'required|exists:user,id',
            'grades.*.score' => 'required|numeric|min:0|max:100'
        ]);

        foreach ($validated['grades'] as $grade) {
            $takenExam = Taken_Exam::where('userId', $grade['studentId'])
                                   ->where('examId', $validated['examId'])
                                   ->firstOrFail();
            if ($request->input('examType') === 'Written') {
                $takenExam->writtenScore = $grade['score'];
            } else {
                $takenExam->oralScore = $grade['score'];
            }
            $takenExam->save();
        }

        return response()->json(['message' => 'Grades successfully assigned']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function assignGradesAndNotify(Request $request){
    try {
        $validated = $request->validate([
            'examId' => 'required|exists:comprehensive_exam,examId',
            'grades' => 'required|array',
            'grades.*.studentId' => 'required|exists:user,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
            'emailSubject' => 'sometimes|required',
            'emailDescription' => 'sometimes|required'
        ]);

        foreach ($validated['grades'] as $grade) {
            $takenExam = Taken_Exam::where('userId', $grade['studentId'])
                                   ->where('examId', $validated['examId'])
                                   ->firstOrFail();

                $takenExam->oralScore = $grade['score'];
                $takenExam->save();
        }

     
        // Fetch students who scored more than 60
        $studentsToNotify = Taken_Exam::where('examId', $validated['examId'])
                                      ->where('oralScore', '>=', 60)
                                      ->with('user') // Make sure there is a relation `user` in Taken_Exam
                                      ->get();

        // Send email to each student
        foreach ($studentsToNotify as $student) {
            if ($student->user) {
                $data = [
                    'subject' => $validated['emailSubject'] ?? 'Congratulations on Passing!',
                    'description' => $validated['emailDescription'] ?? 'You have passed the oral examination.',
                    'firstName' => $student->user->firstName,
                    'lastName' => $student->user->lastName,
                    'fullName' => $student->user->firstName . ' ' . $student->user->lastName,
                    'email' => $student->user->email
                ];

                Mail::to($student->user->email)->send(new PassExam($data));
            }
        }

        return response()->json(['message' => 'Grades successfully assigned and notifications sent']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getStudentsWithGrades(Request $request) {
    $validated = $request->validate([
        'examId' => 'required|exists:comprehensive_exam,examId',
        'examType' => 'required|in:Written,Oral'
    ]);

    $examId = $validated['examId'];
    $examType = $validated['examType'];

    $studentsQuery = DB::table('taken_exam')
        ->join('user', 'taken_exam.userId', '=', 'user.id')
        ->where('taken_exam.examId', $examId);

    if ($examType === 'Written') {
        $students = $studentsQuery
            ->select('user.id as studentId','user.loginId as loginId', DB::raw("concat(user.firstName, ' ', user.lastName) as name"), 'taken_exam.writtenScore as score')
            ->get();
    } else { // Oral exam
        $students = $studentsQuery
            ->where('taken_exam.writtenScore', '>=', 60)
            ->select('user.id as studentId','user.loginId as loginId', DB::raw("concat(user.firstName, ' ', user.lastName) as name"), 'taken_exam.oralScore as score')
            ->get();
    }


    return response()->json(['students' => $students]);
}



    
}

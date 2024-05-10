<?php
 
namespace App\Http\Controllers;
use App\Models\ProgressDescription; 
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Taken_Exam;
use App\Models\Seminars;
use App\Models\Semester;
use App\Models\Publications;
use Carbon\Carbon; 
class ProgressDescriptionController extends Controller
{
    public function fetchData()
    {
        // Fetch all Description  
        $Descriptions = ProgressDescription::all();  
        $formattedData =array();

        foreach ($Descriptions as $Description) {
            $formattedData[] = [
                'id' => $Description->id,
                'title' => $Description->title,
             'description' => $Description->description];
        }

        // Return the  response (you can customize this)
        return response()->json($formattedData);
    }

    public function massage($id)  {
        $user = User::where('loginId', $loginId)->first();
        $student = Student::where('userId', $user->id)->first();
      //  $student = Student::where('userId', $id)->first();
        $unreadNotifications = $student->markUnreadNotificationsAsRead();

        // Extract titles from notifications
        $notificationTitles = $unreadNotifications->map(function ($notification) {
            return $notification->data['title']; // Assuming the title is stored in 'title' property
        });
        $formattedData = [

            "Notifications" => $notificationTitles
        ];
        
        return response()->json($formattedData);
    }
    
    public function progress($loginId)
{
    // Retrieve the user by loginId
    $user = User::where('loginId', $loginId)->first();

    // Ensure that a user is found before proceeding
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Use the user's ID to find the associated student
    $student = Student::where('userId', $user->id)->first();
    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    // Fetch the most recent exam taken by the student
    $studentExam = Taken_Exam::where('userId', $user->id)->latest('created_at')->first();

    // Determine exam status
    $examStatus = ($studentExam && $studentExam->writtenScore >= 60 && $studentExam->oralScore >= 60) ? "passed" : "not passed";
    
    // Fetch the semester that includes today's date
    $today = Carbon::now();
    $currentSemester = Semester::where('start_date', '<=', $today)
                                ->where('end_date', '>=', $today)
                                ->first();

    if (!$currentSemester) {
        return response()->json(['error' => 'No current semester found'], 404);
    }

    // Check seminar attendance status within the current semester
    $seminars = Seminars::where('userId', $user->id)
                        ->whereBetween('date', [$currentSemester->start_date, $currentSemester->end_date])
                        ->get();

    $seminarPresentNum = $seminars->count();
    $seminarPresentStatus = $seminarPresentNum >= 3 ? "passed" : "not passed";

    // Check publication status
    $publications = Publications::where('userId', $user->id)->get();
    $publicationNum = $publications->count();
    $publicationStatus = $publicationNum ? "passed" : "not passed";

    // Collect formatted data for response
    $formattedData = [
        "examStatus" => $examStatus,
        "seminarPresentStatus" => $seminarPresentStatus,
        "seminarPresentNum" => $seminarPresentNum,
        "publicationNum" => $publicationNum,
        "publicationStatus" => $publicationStatus,
    ];

    return response()->json($formattedData);
}
}
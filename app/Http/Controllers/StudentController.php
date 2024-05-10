<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Publications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller {

    public function checkPassword(Request $request) {
        $username = $request->input('userId');
        $password = $request->input('password');

        // Retrieve user from database by username
        $user = Student::where('id', $username)->first();

        if (!$user) {
            return response()->json( 'User not found');
        }

        // Check if password matches
        $datapas=  $user ->password;

        if ($datapas==$password) {
            return response()->json($user);
        } else {
            return response()->json( 'Incorrect password');
        }
    }

    //using in student information component 
    public function fetchUserData($id): \Illuminate\Http\JsonResponse
{
    $user = User::where('loginId', $id)->first();

    if ($user) {
        // Retrieve the associated student data using the relationship method
        $student = $user->students()->first(); // Ensure that this method is defined in your User model.
        $studentArray = $student ? $student->toArray() : [];

        // Prepare the user data
        $userData = [
            'loginId' => $user->loginId,
            'firstName' => $user->firstName,
            'middleName' => $user->middleName, // Corrected typo
            'lastName' => $user->lastName,
            'email' => $user->email,
            'phone' => $user->phone_number,
            'department' => $user->department,
            'section' => $user->gender,
        ];
     
        // If student data is available, merge it with the user data
        if ($student) {
            $studentData = [
                'graduationDate' => $student->calculateExpectedGraduationYear(),
                'withdrawSemester' => $student->countwithdrawSemesters(),
                'postponedSemester' => $student->countpostponedSemesters(),
                'status' => $student->status,
                'enrollYear' => $student->enrollYear,
                'gpa' => $student->gpa,
              'remainingSemesters' => $student->countRemainingSemesters(),
             // 'field'  => $student->$field,
         //  'dissertationStartYear'  => $student->$thesisStartDate,
           
            ];

            // Merge student data with user data
            $userData = array_merge($userData, $studentArray, $studentData);
        }

        return response()->json($userData);
    }

    // If user is not found or data fetching fails, return error response
    return response()->json(['error' => 'User not found'], 404);
}
    public function fetchWithFilter(Request $request)
    {
        $query = DB::table('user')
        ->join('student', 'user.id', '=', 'student.userId')
        ->select(
            'user.id',
            'user.loginId', 
            'user.firstName', 
            'user.lastName', 
            'user.department', 
            'user.email', 
            'user.phone_number'
        );

// Apply filters only if they are present and not empty
if ($request->filled('gender')) {
$query->where('gender', $request->gender);
}
if ($request->filled('department')) {
    $query->where('department', $request->department);
}

    // Start checking for either enroll year or expected graduation
    if ($request->boolean('enrollYear') || $request->boolean('graduationDate')) {
        $query->where(function ($query) use ($request) {
            if ($request->boolean('enrollYear')) {
                $query->orWhere('enrollYear', date('Y'));
            }
            if ($request->boolean('graduationDate')) {
                $query->orWhere('graduationDate',  date('Y'));
            }
        });
    }

if ($request->filled('status')) {
$query->where('status', $request->status);
}

return response()->json($query->get());
    }
    public function delete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }
    
        try {
            // Assuming User is your user model and it is related to Student
            User::destroy($ids);
            return response()->json(['message' => 'Users and their associated students deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function statusData(){
        // Count active students
        $activeCount = Student::where('status', 'active')->count();

        // Count inactive students
        $inactiveCount = Student::where('status', 'inactive')->count();
        $totalNum = Student::count();
        $enrolledYear = Student::whereRaw('YEAR(enrollYear) = ?', [2024])->count();
        $publication = Publications::all();

        // Return counts as JSON response
        return response()->json([
            'active_count' => $activeCount,
            'inactive_count' => $inactiveCount,
            'totalNum'=>$totalNum,
            'enrolledYear' =>$enrolledYear,
            'publications'=> $publication

        ]);
    }

    public function updateUserdata(Request $request){

        try{
            $requestData = $request->all();

            // Find the user by loginId
            $user = User::where('loginId', $requestData['loginId'])->first();

            // Update user data
            if ($user) {
                $user->firstName = $requestData['firstName'];
                $user->lastName = $requestData['lastName'];
                $user->email = $requestData['email'];
                $user->save();
            }

            // Find the student by loginId
            $student = Student::where('userId', $requestData['userId'])->first();

            // Update student data
            if ($student) {
                $student->dateOfBirth = $requestData['dateOfBirth'];
                $student->graduationDate = $requestData['graduationDate'];
                $student->withdrawSemester = $requestData['withdrawSemester'];
                $student->postponedSemester = $requestData['postponedSemester'];
                $student->status = $requestData['status'];
                $student->enrollYear = $requestData['enrollYear'];
                $student->gpa = $requestData['gpa'];
                $student->supervisor = $requestData['supervisor'];
                $student->coSupervisor = $requestData['coSupervisor'];
                $student->phoneNumber = $requestData['phoneNumber'];
                $student->section = $requestData['section'];
                $student->department = $requestData['department'];
                $student->thesisStartDate = $requestData['thesisStartDate'];
                $student->field = $requestData['field'];
                $student->semesterNumber = $requestData['semesterNumber'];
                $student->remainingSemesters = $requestData['remainingSemesters'];
                $student->remainingCourses = $requestData['remainingCourses'];
                $student->dissertationStartYear = $requestData['dissertationStartYear'];
                $student->numberOfSemesters = $requestData['numberOfSemesters'];
                $student->numberOfSeminars = $requestData['numberOfSeminars'];

                // Update any other student fields as needed

                $student->save();
            }

            // You can return a response indicating success or failure
            return response()->json(['success'=> true,'message' => 'Data updated successfully']);
        }

        catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['success'=> false,'message' => 'An error occurred while updating student data.', 'details' => $e->getMessage()], 500);
        }
    }

    }

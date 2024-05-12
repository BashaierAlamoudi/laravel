<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Publications;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     
        if ($student) {
            $studentData = [
                'status' => $student->status,
                'enrollYear' => $student->enrollYear,
                'gpa' => $student->gpa,
                'field'  => $student->field, // This could be null
                'dissertationStartYear'  => $student->thesisStartDate , // Format date or handle null
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
    $currentYear = date('Y'); // Get the current year once and use it for comparison

    // Starting the query by joining user with student on userId
    $query = DB::table('user')
        ->join('student', 'user.id', '=', 'student.userId')
        ->select(
            'user.id',
            'user.loginId', 
            'user.firstName', 
            'user.lastName', 
            'user.department', 
            'user.email', 
            'user.phone_number',
            'user.gender',  // Assuming gender is in the student table
            'student.enrollYear',  // Assuming enrollYear is in the student table
            'student.graduationDate'  // Assuming graduationDate is in the student table
        );

    // Applying filters
    if ($request->filled('gender')) {
        $query->where('student.gender', $request->gender); // Specify table if ambiguous
    }
    if ($request->filled('department')) {
        $query->where('user.department', $request->department); // Specify table if ambiguous
    }
    if ($request->filled('status')) {
        $query->where('student.status', $request->status); // Assuming status is in the student table
    }

    // Conditional filters based on boolean flags
    if ($request->boolean('enrollYear') || $request->boolean('graduationDate')) {
        $query->where(function ($query) use ($request, $currentYear) {
            if ($request->boolean('enrollYear')) {
                // Extract year from date field for comparison
                $query->orWhere(DB::raw('YEAR(student.enrollYear)'), '=', $currentYear);
            }
            if ($request->boolean('graduationDate')) {
                // Assuming graduationDate is also a date and you want to check the current year
                $query->orWhere(DB::raw('YEAR(student.graduationDate)'), '=', $currentYear);
            }
        });
    }

    // Return JSON response with query results
    return response()->json($query->get());
}

    public function getSupervisors($userId) {
        $user = User::find($userId);
        $supervisors = $user->supervisors()->get()->map(function ($supervisor) {
            return [
                'id' => $supervisor->id,
                'name'=>$supervisor->firstName." ".$supervisor->lastName,
                'type' => $supervisor->pivot->type, // Ensure the pivot information is correctly loaded
            ];
        });

        return response()->json(['supervisors' => $supervisors]);
    }

    public function getSupervisorsWithStudents(){
        $supervisors = Supervisor::with(['students' => function ($query) {
            $query->select('user.id', 'user.loginId', 'user.firstName', 'user.lastName');
        }])->get();

        return response()->json($supervisors);
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

public function updateUserdata(Request $request) {
    try {
        $requestData = $request->all();
        $user = User::where('loginId', $requestData['loginId'])->first();

        if ($user) {
            $user->update([
                'firstName' => $requestData['firstName'],
                'middleName' => $requestData['middleName'],
                'lastName' => $requestData['lastName'],
                'email' => $requestData['email'],
                'phone_number' => $requestData['phone'],
                // assuming 'phone' is the correct key
            ]);
        }

        $student = Student::where('userId', $user->id)->first();

        if ($student) {
            $student->update([
                'thesisStartDate' => $requestData['dissertationStartYear'],
                'field'=> $requestData['field'],
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'An error occurred while updating student data.', 'details' => $e->getMessage()], 500);
    }
}

    public function updateSupervisors(Request $request, $userId) {
        $this->validate($request, [
            'mainSupervisorId' => 'required|exists:supervisors,id',
            'coSupervisorId' => 'exists:supervisors,id'
        ]);
    
        DB::transaction(function () use ($request, $userId) {
            $user = User::findOrFail($userId);
            $user->supervisors()->sync([
                $request->mainSupervisorId => ['type' => 'main'],
                $request->coSupervisorId => ['type' => 'co']
            ]);
        });
    
        return response()->json(['message' => 'Supervisors updated successfully']);
    }
    public function getAllSupervisors() {
        $supervisors = Supervisor::all()->map(function ($supervisor) {
            return [
                'id' => $supervisor->id,
                'name' => $supervisor->firstName . ' ' . $supervisor->lastName,
            ];
        });
        return response()->json($supervisors);
    }
    public function getStudentDetails($id) {
        // Prepare your query using Query Builder
        $studentDetails = DB::table('user')
        ->join('student', 'user.id', '=', 'student.userId')
        ->where('student.userId', $id)  // Assuming you are using student.id to identify the student
        ->select(
            'user.id as userId',
            'user.loginId',
            'user.firstName',
            'user.lastName',
            'user.department',
            'user.email',
            'user.phone_number',
           'student.graduationDate',
            'student.withdrawSemester', 
            'student.postponedSemester',// Assuming you want to fetch this from the student table
            'student.status',         // More fields from the student table
            'student.enrollYear',
            'student.gpa'
        )
        ->first();
  
    // Check if the student details were found
    if (!$studentDetails) {
        return response()->json(['message' => 'Student not found'], 404);
    }
  
    return response()->json($studentDetails);
  }

  public function update(Request $request, $id)
  {
      DB::beginTransaction();
      try {
          // Update user details
          $user = User::findOrFail($id);
          $user->update($request->only(['firstName', 'lastName', 'email', 'phone_number', 'department']));

          // Update student details
          if ($user->student) {
              $user->student->update($request->only([ 'status', 'enrollYear', 'gpa']));//['graduationDate', 'status', 'enrollYear', 'gpa']

              if ($request->filled('mainSupervisorId')) {
                  // Detach existing main supervisor
                  $user->supervisors()->wherePivot('type', 'main')->detach();
                  // Attach new main supervisor
                  $user->supervisors()->attach($request->mainSupervisorId, ['type' => 'main']);
              }

              // Handling co-supervisor update
              if ($request->filled('coSupervisorId')) {
                  // Detach existing co-supervisor
                  $user->supervisors()->wherePivot('type', 'co')->detach();
                  // Attach new co-supervisor
                  $user->supervisors()->attach($request->coSupervisorId, ['type' => 'co']);
              }
          } else {
              throw new Exception("No associated student found for user $id");
          }

          DB::commit();
          return response()->json(['message' => 'Updated successfully']);
      } catch (\Exception $e) {
          DB::rollBack();
          Log::error("Update failed for user $id: " . $e->getMessage());
          return response()->json(['message' => 'Update failed', 'error' => $e->getMessage()], 500);
      }
  }





  public function fetchStudentDetails($id)
{
  $studentDetails = DB::table('user')
      ->join('student', 'user.id', '=', 'student.userId')
      ->where('user.id', $id)
      ->select(
          'user.id',
          'user.loginId',
          'user.firstName',
          'user.lastName',
          'user.phone_number',
          'user.email',
          'user.gender',
          'user.department',
         // 'student.graduationDate',
         // 'student.withdrawSemester',
        //  'student.postponedSemester',
          'student.status',
          'student.enrollYear',
          'student.gpa'
      )->first();

  if (!$studentDetails) {
      return response()->json(['message' => 'Student not found'], 404);
  }

  return response()->json($studentDetails);
}

    }

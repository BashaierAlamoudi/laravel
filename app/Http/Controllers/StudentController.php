<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


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

    public function fetchUserData(Request $request): \Illuminate\Http\JsonResponse
    {
        // Retrieve the authenticated user ID from the session
        $userId = 3;

        if ($userId) {
            // Retrieve the user from the database using the user ID
            $user = User::find($userId);

            if ($user) {
                // Assuming you have a relationship defined in your User model to retrieve student data
                $student = $user->students()->first(); // Assuming user has one student relationship

                $userData = [
                    'loginId' => $user->loginId,
                    'firstName' => $user->firstName,
                    'middleName' => $user->middletName, // Typo: It should be 'middleName', not 'middletName'
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                ];

                if ($student) {
                    $studentData = [
                        'graduationDate' => $student->graduationDate,
                        'withdrawSemester' => $student->withdrawSemester,
                        'postponedSemester' => $student->postponedSemester,
                        'status' => $student->status,
                        'enrollYear' => $student->enrollYear,
                        'gpa' => $student->gpa,
                    ];

                    // Merge student data with user data
                    $userData = array_merge($userData, $studentData);
                }

                // Log fetched data to the console

                return response()->json($userData);
            }
        }

        // If user is not authenticated or data fetching fails, return error response
        return response()->json(['error' => 'User not authenticated'], 401);
    }



}

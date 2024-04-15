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

    public function fetchUserData($id): \Illuminate\Http\JsonResponse
    {
        
           $user = User::where('loginId', $id)->first();

            if ($user) {
                // Retrieve the associated student data using the relationship method
                $student = $user->students()->first();
    
                // Prepare the user data
                $userData = [
                    'loginId' => $user->loginId,
                    'firstName' => $user->firstName,
                    'middleName' => $user->middletName, // Typo: It should be 'middleName', not 'middletName'
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                ];
    
                // If student data is available, merge it with the user data
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
        
    
        // If user is not found or data fetching fails, return error response
        return response()->json(['error' => 'User not found'], 404);
    }
    }
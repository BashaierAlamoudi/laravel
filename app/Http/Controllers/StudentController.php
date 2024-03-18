<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\hash;


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
    
}
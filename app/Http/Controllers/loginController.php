<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Coordinator;
use App\Models\User;



class loginController extends Controller
{
    public function login(Request $request){
            $userId = $request->input('loginId');
            $password = $request->input('password');
    
            // Retrieve user from database by username
            $user = User::where('loginId', $userId)->first();
            
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

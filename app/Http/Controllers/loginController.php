<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    public function login(Request $request)
    {
        $userId = $request->input('loginId');
        $password = $request->input('password');
        $request->session()->start();

        // Retrieve user from database by username
        $user = User::where('loginId', $userId)->first();

        if (!$user) {
            return response()->json('User not found', 404);
        }

        // Check if password matches
        if ($user->password === $password) {
            // Manually create a session for the user
            $request->session()->put('temp_user_id', $user->id);

            // Retrieve the authenticated user
            $authenticatedUser = User::find($request->session()->get('temp_user_id'));

            // Return the authenticated user as JSON response
            return response()->json($authenticatedUser);
        } else {
            return response()->json('Incorrect password', 401);
        }
    }

}

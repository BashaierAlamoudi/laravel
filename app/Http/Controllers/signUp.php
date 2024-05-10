<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\New_Student;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\newStudent;
use App\Mail\forgotPassword;

use Illuminate\Http\Request;

class signUp extends Controller
{
    public function fetchData()
    {
        $newStudents = New_Student::all();  
        $formattedData =array();

        foreach ($newStudents as $newStudent) {
            $formattedData[] = [
                'id'=>$newStudent->id,
                'loginId' => $newStudent->userId,
                'nationalId' => $newStudent->nationalId,
                'department' => $newStudent->department,
                'StudentName' => $newStudent->firstName . ' ' . $newStudent->middleName . ' ' . $newStudent->lastName,
                'section'=>$newStudent->section,
                'email'=>$newStudent->email,
                'gpa'=>$newStudent->gpa,
                'phone_number'=>$newStudent->phone_number,
                'YearEnroll'=>$newStudent->enrollYear,

                
            ];
        }
        return response()->json($formattedData);
    }

            
    public function AddStudent(Request $request){
            $newStudent = new New_Student ([
            'userId' => $request['loginId'],
            'password' => $request['confirampassword'],
            'firstName' => $request['firstName'],
            'middleName' => $request['middleName'],
            'lastName' => $request['lastName'],
            'enrollYear' => $request['yearEnroll'],
            'gpa' => $request['gpa'],
            'phone_number'=>$request['phoneNumber'],
            'email'=>$request['email'],
            'department'=>$request['department'],
            'section'=>$request['section'],
            'nationalId'=>$request['nationalId'],
        ]);

        $newStudent->save();
        return response()->json(['message' => 'Student added successfully'], 200);
    }
    public function acceptStudent(Request $request ){
        $password=$this->generatePassword();
        $nameParts = explode(' ', $request->input('StudentName'));

        $firstName = $nameParts[0] ?? '';
        $middleName = implode(' ', array_slice($nameParts, 1, -1)) ?? ''; // Join middle names with spaces
        $lastName = end($nameParts) ?? ''; 
        //return response()->json($middleName);

        $newUser = new User([
            'loginId'=>$request['loginId'],
            'password'=>$password,
            'firstName'=>$firstName,
            'middleName'=>$middleName,
            'lastName'=>$lastName,
            'phone_number'=>$request['phone_number'],
            'email'=>$request['email'],
            'department'=>$request['department'],
            'gender'=>$request['section'],
            'role'=>'student',
            'nationalId'=>$request['nationalId']
        ]);
        $newUser-> save();
        $newStudent = new Student([
            'userId'=>$newUser['id'],
            'enrollYear'=>$request['YearEnroll'],
            'gpa'=>$request['gpa'],
            'status'=>'active',
            'withdrawSemester'=>0,
            'postponedSemester'=>0,
            'graduationDate'=>$request['YearEnroll'],
        ]);
        $newStudent->save();
        $this->sendMail($newUser->id);
        $this->delete($request->id);



    }
    public function delete($id){
        $student = New_Student::where('id',$id )->first();
        $student ->delete();


    }
    public function sendMail($id){
    $user = User::where('id', $id)->first();

    $data = [
        'subject' => 'Welcome To Super!',
        'password'=>$user->password,
        'firstName' => $user->firstName,
        'lastName' => $user->lastName,
        'fullName' => $user->firstName . ' ' . $user->lastName,
        'email' => $user->email
    ];

    Mail::to($user->email)->send(new newStudent($data));
}

public function forgotPassword($email){
    $user = User::where('email', $email)->first();
    if($email){
        $newPassword = $this->generatePassword();
        $user->password = $newPassword;
        $user->save();
        $data = [
            'subject' => 'New Password For SUPER!',
            'password'=>$user->password,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'fullName' => $user->firstName . ' ' . $user->lastName,
            'email' => $user->email
        ];

    Mail::to($user->email)->send(new forgotPassword($data));}
    else{
        return response()->json('no email');

    }

}


     


    public function generatePassword($length = 8)
    {
        return Str::random($length);
    }
}

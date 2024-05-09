<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\New_Student; // Import the New_Student model
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class NewStudentController extends Controller
{
    



    public function fetchData()
    {
        // Fetch all new students from the Event model
        $newStudents = $New_Student::all();  
        $formattedData =array();

        foreach ($newStudents as $newStudent) {
            $formattedData[] = [
                'id'=>$newStudent->id,
                'loginId' => $newStudent->userId,
                'nationalId' => $newStudent->nationalId,
                'department' => $newStudent->department,
                'StudentName' => ($newStuden->firstName+$newStuden->middletName+$newStuden->lastName),
                'section'=>$newStudent->section,
                'email'=>$newStudent->email,
                'gpa'=>$newStudent->gpa,
                'phone_number'=>$newStudent->phone_number,

                
            ];
        }
        return response()->json($formattedData);
    }
  
    
    

    public function acceptStudent(Request $request, $studentId)
    {
        $newStudent = New_Student::find($studentId);
    
        if ($newStudent) {
            $user = new User([
                'loginId' => $newStudent->userId,
               // 'password' => Hash::make($newStudent->password), // Hash the password before saving
               'password' => $newStudent->password,
               'firstName' => $newStudent->firstName,
                'middletName' => $newStudent->middletName, // Corrected spelling of 'middleName'
                'lastName' => $newStudent->lastName,
                'phone_number' => $newStudent->phone_number, // Corrected naming convention to snake_case
                'email' => $newStudent->email,
                'department' => $newStudent->department,
                'role' => 'student',
            ]);
    
            $user->save();
            $newStudent->delete();
            return response()->json(['message' => 'Student added successfully', 'data' => $user], 201);
        }
    
        // Return an error response if the student does not exist
        return response()->json(['message' => 'Student not found'], 404);
    }


    
    public function delete($studentId)
    {
        $id = intval($studentId);
        // Delete student instance
       
        $newStudent = New_Student::find($studentId);
    
        $newStudent->delete();
   
    }
    
}

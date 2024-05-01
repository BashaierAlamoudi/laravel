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
        // Fetch all new students from the New_Student model
        $newStudents = New_Student::all();  

        $formattedStudents = array();

        foreach ($newStudents as $newStudent) {
            $formattedStudents[] = [
                'loginId' => $newStudent->userId,
                'nationalId' => $newStudent->nationalId,
                'department' => $newStudent->department,
                'StudentName' => $newStudent->firstName . ' ' . $newStudent->middletName . ' ' . $newStudent->lastName,
                'section' => $newStudent->section,
                'email' => $newStudent->email,
            ];
        }

        // Return the new students as a response (you can customize this)
        return response()->json($formattedStudents);
    }


    public function add(Request $request)
    {

        $newStudent = new New_Student ([
            'userId' => $request['loginId'],
            'password' => $request['password'],
            'nationalId' => $request['nationalId'],
            'enrollYear' => $request['yearEnroll'],
            'department' => $request['department'],
            'firstName' => $request['firstName'],
            'middletName'=> $request['middleName'],
            'lastName'=> $request['lastName'],
            'email' => $request['email'],
            'section' => $request['section'],
             'gpa'=> $request['gpa'],
            'phone_number'=> $request['phoneNumber'],
        ]);
         
       

        // Save the new Event instance to the database
        $newStudent ->save();
    
        //  return a response to indicate success or failure
        return response()->json(['message' => 'student  instance saved successfully']);
    }

    public function fetchData1()
    {
        // Fetch all new students from the Event model
        $newStudents = $New_Student::all();  
        $formattedEvents =array();

        foreach ($newStudents as $newStudent1) {
            $formattedEvents[] = [
                'loginId' => $newStuden1->userId,
                'nationalId' => $newStuden1->nationalId,
                'department' => $newStuden1->department,
                'StudentName' => ($newStuden->firstName+$newStuden->middletName+$newStuden->lastName),
                'section'=>$newStuden1->section,
                'email'=>$newStuden1->email,
                
            ];
        }

        // Return the new students as a response (you can customize this)
        return response()->json($formattedEvents);
    }
  
    public function fetchDat()
    {
        // Fetch all new students from the New_Student model
        $newStudents = New_Student::all();  

        $formattedStudents = array();

        foreach ($newStudents as $newStudent2) {
            $formattedStudents[] = [
                'loginId' => $newStudent2->userId,
                'nationalId' => $newStudent2->nationalId,
                'department' => $newStudent2->department,
                'StudentName' => $newStudent2->firstName . ' ' . $newStudent2->middletName . ' ' . $newStudent2->lastName,
                'Section' => $newStudent2->section,
                'email' => $newStudent2->email,
            ];
        }

        // Return the new students as a response (you can customize this)
        return response()->json($formattedStudents);
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

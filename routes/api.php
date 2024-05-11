<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComprehensiveController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\signUp; 
use App\Http\Controllers\emailcontroller;
use App\Http\Controllers\ProgressDescriptionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//assign-grades-and-notify
Route::get('/ComprehensiveExam', [ComprehensiveController::class, 'fetchData']);
Route::post('/ComprehensiveExam/edit/{id}', [ComprehensiveController::class, 'update']);
Route::post('/ComprehensiveExam/delete/{id}', [ComprehensiveController::class, 'delete']);
Route::post('/ComprehensiveExam/add/', [ComprehensiveController::class, 'add']);
Route::get('/ComprehensiveExam/students', [ComprehensiveController::class, 'getStudentsByYearAndSeason']);
Route::post('/ComprehensiveExam/assign-grades', [ComprehensiveController::class, 'assignGrades']);
Route::post('/ComprehensiveExam/assign-grades-and-notify', [ComprehensiveController::class, 'assignGradesAndNotify']);
Route::post('/ComprehensiveExam/new', [ComprehensiveController::class, 'addNewExam']);
Route::get('ComprehensiveExam/exams', [ComprehensiveController::class, 'index']);
Route::post('/ComprehensiveExam/assign-students-to-exam', [ComprehensiveController::class, 'assignStudentsToExam']);
Route::post('/ComprehensiveExam/notify-exam', [ComprehensiveController::class, 'notifyExam']);
Route::post('/ComprehensiveExam/get-students-with-grades', [ComprehensiveController::class, 'getStudentsWithGrades']);
Route::get('/exams/{examId}/students', [ComprehensiveController::class, 'getStudentsByExamId']);

Route::get('/StudentSeminars', [SeminarController::class, 'fetchStudentsData']);
Route::get('/PublicSeminars', [SeminarController::class, 'fetchData']);
Route::get('/UserSeminars/{id}', [SeminarController::class, 'fetchUserData']);
Route::post('/Seminars/edit/{id}', [SeminarController::class, 'update']);
Route::post('/Seminars/delete/{id}', [SeminarController::class, 'delete']);
Route::post('/Seminars/add/', [SeminarController::class, 'add']);


Route::get('/publications', [PublicationController::class, 'fetchData']);
Route::get('/studentpublications/{id}', [PublicationController::class, 'fetchStudentData']);
Route::post('/publications/edit/{id}', [PublicationController::class, 'update']);
Route::post('/publications/delete/{id}', [PublicationController::class, 'delete']);
Route::post('/publications/add/', [PublicationController::class, 'add']);
Route::get('/publications/pdf/{filename}', [RuleController::class, 'getPdf']);

Route::get('/rules/pdf/{filename}', [PublicationController::class, 'getPdfUrl']);



Route::get('/event',[EventController::class,'fetchData']);
Route::post('/event/add/', [EventController::class, 'add']);
Route::post('/event/delete/{id}', [EventController::class, 'delete']);
Route::post('/event/edit/{id}', [EventController::class, 'update']);

Route::get('/fetch-user-data/{id}', [StudentController::class, 'fetchUserData']);
Route::post('/update-user-data', [StudentController::class, 'updateUserdata']);
Route::get('/statusData', [StudentController::class, 'statusData']);
Route::get('/student/student-filter', [StudentController::class, 'fetchWithFilter']);
Route::put('/student/update/{id}', [StudentController::class, 'update']);
Route::delete('/students/delete', [StudentController::class, 'delete']);
Route::post('/login',[loginController::class,'login']);
Route::get('/student/details/{id}', [StudentController::class, 'getStudentDetails']);
Route::get('/students/{userId}/supervisors', [StudentController::class, 'getSupervisors']);
Route::get('/students/supervisors',  [StudentController::class, 'getAllSupervisors']);
Route::put('/students/{userId}/update-supervisors',  [StudentController::class, 'updateSupervisors']);
Route::get('students/supervisors-with-their-students', [StudentController::class, 'getSupervisorsWithStudents']);

Route::get('/Rule/fetchRule',[RuleController::class,'fetchRules']);
Route::get('/Rule/fetchFAQs',[RuleController::class,'fetchFAQs']);
Route::post('rules/add',[RuleController::class,'addRule']);




Route::post('/signup',[signUp::class,'AddStudent']);

Route::post('/student/acceptStudent',[signUp::class,'acceptStudent']);
Route::get('/student/fetchNewStudents',[signUp::class,'fetchData']);

Route::get('/student/resetPassword/{email}',[signUp::class,'forgotPassword']);


Route::get('/Database-panel/newstudent',[NewStudentController::class,'fetchData']);
Route::post('/Database-panel/accept/{id}', [NewStudentController::class, 'acceptStudent']);
Route::post('/Database-panel/reject/{id}', [NewStudentController::class, 'delete']);


Route::get('/delete/{id}', [signUp::class, 'reject']);

Route::get('/sendMail', [emailcontroller::class, 'sendTestEmail']);

Route::get('/progress/progress-description',[ProgressDescriptionController::class,'fetchData']);
Route::get('/progress/progress-tracker/{id}',[ProgressDescriptionController::class,'progress']);




Route::get('/SeminarAttendances/{id}', [SeminarController::class, 'fetchStudentAttendance']);
Route::post('/SeminarAttendances/edit/{id}', [SeminarController::class, 'updateSeminarAttendance']);
Route::post('/SeminarAttendances/delete/{id}', [SeminarController::class, 'deleteSeminarAttendance']);
Route::post('/SeminarAttendances/add/', [SeminarController::class, 'addSeminarAttendance']);
Route::get('/SeminarAttendances/pdf/{filename}', [SeminarController::class, 'getPdf']);
Route::get('/MessageNotification/{$id}',[ProgressDescriptionController::class,'massage']);
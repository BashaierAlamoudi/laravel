<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Semester;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnrollActiveStudents extends Command
{
    protected $signature = 'enroll:active_students';
    protected $description = 'Enrolls active students into the current semester and updates enrollments for inactive ones.';

    public function handle()
    {
        $currentDate = Carbon::now();
        $semester = Semester::where('start_date', '<=', $currentDate)
                            ->where('end_date', '>=', $currentDate)
                            ->first();

        if (!$semester) {
            $this->info('No active semester currently.');
            return;
        }

        $activeStudents = Student::where('status', 'active')->get();
        foreach ($activeStudents as $student) {
            Enrollment::firstOrCreate([
                'status'=>'active',
                'student_id' => $student->id,
                'semester_id' => $semester->id,
            ]);
            $this->info("Enrolled student {$student->id} in semester {$semester->id}.");
        }

        // Fetch current enrollments for the active semester
        $currentEnrollments = Enrollment::where('semester_id', $semester->id)->get();

        foreach ($currentEnrollments as $enrollment) {
            $student = $enrollment->student;
            if (!$student) {
                // Handle the case where no student is found for an enrollment
                $this->info("No student found for enrollment ID: {$enrollment->id}. Deleting enrollment.");
                $enrollment->delete();
            } else if ($student->status !== 'active') {
                // Update the enrollment status to match the student's status instead of deleting
                $this->info("Updating status for inactive student from enrollment ID: {$enrollment->id}");
                $enrollment->status = $student->status;
                $enrollment->save(); // Save the updated enrollment status
            }
        }

        $this->info('All active students have been enrolled in the current semester.');
        $this->info('Current date: ' . $currentDate->toDateString());
        $this->info('Found semester: ' . $semester->name . ' from ' . $semester->start_date . ' to ' . $semester->end_date);
    }
}

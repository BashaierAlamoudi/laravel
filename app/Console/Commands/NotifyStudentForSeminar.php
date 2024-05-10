<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Seminars;
use App\Notifications\SeminarNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyStudentForSeminar extends Command
{
    protected $signature = 'notify:seminar';
    protected $description = 'Notify students to have a seminar after two semesters of their dissertation start year';

    public function handle()
    {
        $this->info('Scanning for students who need to be notified about the seminar...');

        $students = Student::whereNotNull('thesisStartDate')->get();

        foreach ($students as $student) {
            $dissertationStartDate = Carbon::parse($student->thesisStartDate);
            $hadSeminar = Seminars::where('student_id', $student->id)  
                                 ->where('date', '>=', $dissertationStartDate)
                                 ->exists();

            if (!$hadSeminar) {
                $this->checkEnrollments($student, $dissertationStartDate);
            } else {
                $this->checkEnrollmentsPostSeminar($student, $dissertationStartDate);
            }
        }

        $this->info('Notification process complete.');
    }

    protected function checkEnrollments($student, $startDate)
    {
        $enrollments = Enrollment::where('student_id', $student->id)
                                 ->where('status', 'active') // Filter for active enrollments
                                 ->whereHas('semester', function ($query) use ($startDate) {
                                     $query->where('start_date', '>', $startDate);
                                 })
                                 ->get();

        if ($enrollments->count() == 3 && $enrollments->last()->semester->isCurrent()) {
            $student->notify(new SeminarNotification());
            $this->info("Notified student: {$student->name} (Supervisor: {$student->supervisor})");
        }
    }

    protected function checkEnrollmentsPostSeminar($student, $startDate)
    {
        $latestSeminar = Seminars::where('student_id', $student->id)
                                ->where('date', '>=', $startDate)
                                ->latest('date')
                                ->first();

        if ($latestSeminar) {
            $semDate = Carbon::parse($latestSeminar->date);
            $enrollments = Enrollment::where('student_id', $student->id)
                                     ->where('status', 'active') // Filter for active enrollments
                                     ->whereHas('semester', function ($query) use ($semDate) {
                                         $query->where('start_date', '>', $semDate);
                                     })
                                     ->get();

            if ($enrollments->count() >= 3 && $enrollments[2]->semester->isCurrent()) {
                $student->notify(new SeminarNotification());
                $this->info("Notified student: {$student->name} (Supervisor: {$student->supervisor})");
            }
        }
    }
}



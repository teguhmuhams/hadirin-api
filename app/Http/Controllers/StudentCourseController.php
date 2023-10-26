<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentCourseController extends Controller
{
    public function enrollStudentToCourse($studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);
        $student->courses()->attach($courseId);

        return response()->json([
            'message' => 'Successfully enrolled student to the course'
        ]);
    }
}

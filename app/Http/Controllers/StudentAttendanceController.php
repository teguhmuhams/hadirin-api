<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentAttendanceSaveRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function enrollStudentToAttendance(StudentAttendanceSaveRequest $request, $studentId, $attendanceId)
    {
        $student = Student::findOrFail($studentId);
        $student->attendances()->attach($attendanceId, [
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Successfully enrolled student to the attendances'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentSaveRequest;
use App\Http\Resources\StudentResource;
use App\Models\User;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Student::class)
            ->allowedIncludes(['user', 'grade'])
            ->allowedSorts('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('nisn'),
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\StudentSaveRequest  $request
     * @param  \App\Models\Student $student
     * @return JsonResponse
     */
    public function store(StudentSaveRequest $request, Student $student): JsonResponse
    {
        $student->fill($request->only($student->offsetGet('fillable')));

        if ($request->has('email') && $request->has('password') && $request->has('role')) {
            $user = User::create(
                [
                    'identifier' => $request->nisn,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role'  => User::ROLE_STUDENT,
                    'email_verified_at' => Carbon::now(),
                ]
            );
            $student->user_id = $user->id;
        }

        $student->save();

        $resource = (new StudentResource($student))
            ->additional(['info' => 'The new student has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return QueryBuilder::for(Student::class)
            ->allowedIncludes(['user'])
            ->findOrFail($student->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\StudentSaveRequest  $request
     * @param \App\Model\Student $student
     * @return \App\Http\Resources\StudentResource
     */
    public function update(StudentSaveRequest $request, Student $student): JsonResponse
    {
        $student->fill($request->only($student->offsetGet('fillable')));

        if ($student->isDirty()) {
            $user = $student->user;
            if ($student->isDirty('nisn')) {
                $user->identifier = $student->nisn;
                $user->save();
            }
            if ($request->has('email')) {
                $user->email = $request->email;
                $user->save();
            }
            $student->save();
        }

        return response()->json([
            'message' => 'Student updated successfully!',
            'data' => $student
        ], 200);
    }

    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student): JsonResponse
    {
        $user = $student->user;

        $student->delete();

        if ($user)
            $user->delete();

        return response()->json([
            'message' => 'Student deleted successfully!'
        ], 200);
    }
}

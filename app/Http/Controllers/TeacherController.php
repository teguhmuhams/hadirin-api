<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherSaveRequest;
use App\Http\Resources\TeacherResource;
use App\Models\User;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeacherController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Teacher::class)
            ->allowedIncludes(['user'])
            ->allowedSorts('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('nip'),
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\TeacherSaveRequest  $request
     * @param  \App\Models\Teacher $teacher
     * @return JsonResponse
     */
    public function store(TeacherSaveRequest $request, Teacher $teacher): JsonResponse
    {
        $teacher->fill($request->only($teacher->offsetGet('fillable')));

        if ($request->has('email') && $request->has('password') && $request->has('role')) {
            $user = User::create(
                [
                    'identifier' => $request->nip,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role'  => User::ROLE_TEACHER,
                    'email_verified_at' => Carbon::now(),
                ]
            );
            $teacher->user_id = $user->id;
        }

        $teacher->save();

        $resource = (new TeacherResource($teacher))
            ->additional(['info' => 'The new teacher has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        return QueryBuilder::for(Teacher::class)
            ->allowedIncludes(['user'])
            ->findOrFail($teacher->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\TeacherSaveRequest  $request
     * @param \App\Model\Teacher $teacher
     * @return \App\Http\Resources\TeacherResource
     */
    public function update(TeacherSaveRequest $request, Teacher $teacher): JsonResponse
    {
        $teacher->fill($request->only($teacher->offsetGet('fillable')));

        if ($teacher->isDirty()) {
            if ($teacher->isDirty('nip')) {
                $user = $teacher->user;
                $user->identifier = $teacher->nip;
                $user->save();
            }
            $teacher->save();
        }

        return response()->json([
            'message' => 'Teacher updated successfully!',
            'data' => $teacher
        ], 200);
    }


    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher): JsonResponse
    {
        $user = $teacher->user;

        $teacher->delete();

        if ($user)
            $user->delete();

        return response()->json([
            'message' => 'Teacher deleted successfully!'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherSaveRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
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
     * @return \App\Http\Resources\TeacherResource
     */
    public function store(TeacherSaveRequest $request)
    {
        $validated = $request->validated();

        $teacher = Teacher::create($validated);

        return new TeacherResource($teacher);
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
            ->allowedIncludes(['users'])
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

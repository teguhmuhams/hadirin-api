<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceSaveRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Attendance::class)
            ->allowedIncludes(['users', 'course'])
            ->allowedSorts(['id', 'created_at'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('title'),
                AllowedFilter::exact('course_id'),
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\AttendanceSaveRequest  $request
     * @param  \App\Models\Attendance $attendance
     * @return JsonResponse
     */
    public function store(AttendanceSaveRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance->fill($request->only($attendance->offsetGet('fillable')));

        $attendance->save();

        $resource = (new AttendanceResource($attendance))
            ->additional(['info' => 'The new Attendance has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        return QueryBuilder::for(Attendance::class)
            ->allowedIncludes(['course'])
            ->findOrFail($attendance->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\AttendanceSaveRequest  $request
     * @param \App\Model\Attendance $attendance
     * @return \App\Http\Resources\AttendanceResource
     */
    public function update(AttendanceSaveRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance->fill($request->only($attendance->offsetGet('fillable')));

        if ($attendance->isDirty()) {
            $attendance->save();
        }

        return response()->json([
            'message' => 'Attendance updated successfully!',
            'data' => $attendance
        ], 200);
    }


    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance): JsonResponse
    {
        if ($attendance)
            $attendance->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully!'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseSaveRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Course::class)
            ->allowedIncludes(['grade', 'teacher', 'students', 'attendances'])
            ->allowedSorts(['id', 'created_at'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('name'),
                AllowedFilter::exact('year'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('grade_id'),
                AllowedFilter::exact('teacher_id'),
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\CourseSaveRequest  $request
     * @param  \App\Models\Course $course
     * @return JsonResponse
     */
    public function store(CourseSaveRequest $request, Course $course): JsonResponse
    {
        $course->fill($request->only($course->offsetGet('fillable')));

        $course->save();

        $resource = (new CourseResource($course))
            ->additional(['info' => 'The new Course has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return QueryBuilder::for(Course::class)
            ->allowedIncludes(['teacher', 'grade'])
            ->findOrFail($course->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\CourseSaveRequest  $request
     * @param \App\Model\Course $course
     * @return \App\Http\Resources\CourseResource
     */
    public function update(CourseSaveRequest $request, Course $course): JsonResponse
    {
        $course->fill($request->only($course->offsetGet('fillable')));

        if ($course->isDirty()) {
            $course->save();
        }

        return response()->json([
            'message' => 'Course updated successfully!',
            'data' => $course
        ], 200);
    }


    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course): JsonResponse
    {
        if ($course)
            $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully!'
        ], 200);
    }
}

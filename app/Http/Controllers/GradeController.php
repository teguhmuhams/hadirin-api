<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeSaveRequest;
use App\Http\Resources\GradeResource;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class GradeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Grade::class)
            ->allowedIncludes(['course'])
            ->allowedSorts('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name',
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\GradeSaveRequest  $request
     * @return \App\Http\Resources\GradeResource
     */
    public function store(GradeSaveRequest $request)
    {
        $validated = $request->validated();

        $grade = Grade::create($validated);
        //$grade->createToken('grade-token')->plainTextToken;

        return new GradeResource($grade);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        return QueryBuilder::for(Grade::class)
            ->allowedIncludes([''])
            ->findOrFail($grade->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\GradeSaveRequest  $request
     * @param \App\Model\Grade $grade
     * @return \App\Http\Resources\GradeResource
     */
    public function update(GradeSaveRequest $request, Grade $grade): JsonResponse
    {
        if ($grade) {
            $grade->fill($request->only($grade->getFillable()));

            if ($grade->isDirty()) {
                $grade->save();
            }
        }
        return response()->json([
            'message' => 'Grade updated successfully!',
            'data' => new GradeResource($grade),
        ], 200);
    }

    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Grade $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade): JsonResponse
    {
        $grade->delete();

        return response()->json([
            'message' => 'Grade deleted successfully!'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSaveRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(User::class)
            ->allowedIncludes(['teacher', 'student', 'admin'])
            ->allowedSorts('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'email',
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\UserSaveRequest  $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(UserSaveRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);
        $user->createToken('user-token')->plainTextToken;

        return new UserResource($user);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return QueryBuilder::for(User::class)
            ->allowedIncludes([''])
            ->findOrFail($user->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\UserSaveRequest  $request
     * @param \App\Model\User $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(UserSaveRequest $request, User $user): JsonResponse
    {
        if ($user) {
            $user->fill($request->only($user->getFillable()));

            if ($user->isDirty()) {
                if ($user->isDirty('identifier')) {
                    switch ($user->role) {
                        case User::ROLE_ADMIN:
                            $user->admin->update(['employee_number' => $user->identifier]);
                            break;

                        case User::ROLE_TEACHER:
                            $user->teacher->update(['nip' => $user->identifier]);
                            break;

                        case User::ROLE_STUDENT:
                            $user->student->update(['nisn' => $user->identifier]);
                            break;
                    }
                }
                $user->save();
            }
        }
        return response()->json([
            'message' => 'User updated successfully!',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Delete the specified resource.
     *
     * @param \App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully!'
        ], 200);
    }
}

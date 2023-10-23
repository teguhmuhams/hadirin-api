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
    public function index()
    {
        return QueryBuilder::for(User::class)
            ->allowedIncludes([''])
            ->allowedSorts('id', 'title')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name',
                'email'
            ])
            ->defaultSort('id')
            ->paginate();
    }

    public function store(UserSaveRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return new UserResource($user);
    }


    public function show(User $user)
    {
        return QueryBuilder::for(User::class)
            ->allowedIncludes([''])
            ->findOrFail($user->id);
    }

    public function update(UserSaveRequest $request, int $id): JsonResponse
    {
        $data = User::findOrFail($id);

        $data->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully!',
            'data' => $data
        ], 200);
    }

    public function destroy(int $id)
    {
        $data = User::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'User deleted successfully!'
        ], 200);
    }
}

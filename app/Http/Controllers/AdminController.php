<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminSaveRequest;
use App\Http\Resources\AdminResource;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Admin::class)
            ->allowedIncludes(['user'])
            ->allowedSorts('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('employee_number'),
            ])
            ->defaultSort('id')
            ->paginate();
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \App\Http\Requests\AdminSaveRequest  $request
     * @param  \App\Models\Admin $admin
     * @return JsonResponse
     */
    public function store(AdminSaveRequest $request, Admin $admin): JsonResponse
    {
        $admin->fill($request->only($admin->offsetGet('fillable')));

        if ($request->has('email') && $request->has('password') && $request->has('role')) {
            $user = User::create(
                [
                    'identifier' => $request->employee_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role'  => User::ROLE_ADMIN,
                    'email_verified_at' => Carbon::now(),
                ]
            );
            $admin->user_id = $user->id;
        }

        $admin->save();

        $resource = (new AdminResource($admin))
            ->additional(['info' => 'The new admin has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return QueryBuilder::for(Admin::class)
            ->allowedIncludes(['user'])
            ->findOrFail($admin->id);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Http\Requests\AdminSaveRequest  $request
     * @param \App\Model\Admin $admin
     * @return \App\Http\Resources\AdminResource
     */
    public function update(AdminSaveRequest $request, Admin $admin): JsonResponse
    {
        $admin->fill($request->only($admin->offsetGet('fillable')));

        if ($admin->isDirty()) {
            $user = $admin->user;
            if ($admin->isDirty('employee_number')) {
                $user->identifier = $admin->employee_number;
                $user->save();
            }
            if($request->has('email')){
                $user->email = $request->email;
                $user->save();
            }
            $admin->save();
        }

        return response()->json([
            'message' => 'Admin updated successfully!',
            'data' => $admin
        ], 200);
    }


    /**
     * Delete the specified resource.
     *
     * @param \App\Model\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin): JsonResponse
    {
        $user = $admin->user;

        $admin->delete();

        if ($user)
            $user->delete();

        return response()->json([
            'message' => 'Admin deleted successfully!'
        ], 200);
    }
}

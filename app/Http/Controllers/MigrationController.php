<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Response;

class MigrationController extends Controller
{
    public function migrate(Request $request)
    {
        try {
            $message = 'Migrations executed successfully';

            if ($request->input('fresh')) {
                Artisan::call('migrate:fresh', ["--force" => true, "--seed" => true]);
                $message =  'Fresh migrations & seeding executed successfully';
            }

            Artisan::call('migrate', ["--force" => true]); // Use --force to run without confirmation prompt
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

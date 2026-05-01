<?php

namespace App\Packages\Installer\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Packages\Installer\Helpers\DatabaseManager;
use App\Packages\Installer\Helpers\EnvironmentManager;
use App\Packages\Installer\Helpers\ServerRequirements;

class InstallerController extends Controller
{
    const MIN_PHP_VERSION = '8.3.0';

    public function __construct(
        protected ServerRequirements $serverRequirements,
        protected EnvironmentManager $environmentManager,
        protected DatabaseManager $databaseManager
    ) {}

    public function index()
    {
        $phpVersion = $this->serverRequirements->checkPHPversion(self::MIN_PHP_VERSION);
        $requirements = $this->serverRequirements->validate();

        return view('installer::installer.index', compact('requirements', 'phpVersion'));
    }

    public function envFileSetup(Request $request): JsonResponse
    {
        $rules = [
            'db_prefix'    => 'not_regex:/[^A-Za-z0-9_]/',
            'db_hostname'  => 'required',
            'db_name'      => 'required',
            'db_username'  => 'required',
            'db_connection'=> 'required|in:mysql,pgsql',
        ];

        $data = array_map('strip_tags', $request->all());

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $message = $this->environmentManager->generateEnv($data);

        return new JsonResponse(['data' => $message]);
    }

    public function runMigration(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        $result = $this->databaseManager->migration();

        if ($result instanceof JsonResponse) {
            return $result;
        }

        return response()->json(['success' => true]);
    }

    public function runSeeder(): JsonResponse
    {
        try {
            $this->databaseManager->seeder();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function adminConfigSetup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $userId = DB::table('users')->insertGetId([
                'name'       => $request->input('name'),
                'email'      => $request->input('email'),
                'password'   => Hash::make($request->input('password')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign super-admin role if spatie permissions are set up
            try {
                $user = \App\Models\User::find($userId);
                if ($user && \Spatie\Permission\Models\Role::where('name', 'super-admin')->exists()) {
                    $user->assignRole('super-admin');
                }
            } catch (\Exception $e) {
                // Role assignment is optional
            }

            File::put(storage_path('installed'), 'School Management App installed on '.now()->toDateTimeString());

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

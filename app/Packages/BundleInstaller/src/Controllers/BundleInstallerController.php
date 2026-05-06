<?php

namespace App\Packages\BundleInstaller\Controllers;

use App\Packages\BundleInstaller\Services\BundleExtractorService;
use App\Packages\BundleInstaller\Services\BundleRegistrarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // used by upload()
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BundleInstallerController extends BaseController
{
    public function __construct(
        private BundleExtractorService $extractor,
        private BundleRegistrarService $registrar,
    ) {}

    public function index(): View
    {
        $packagesPath = base_path('app/Packages');
        $allBundles = [];

        if (is_dir($packagesPath)) {
            $this->findBundles($packagesPath, '', $allBundles);
        }

        $providersContent = file_get_contents(base_path('bootstrap/providers.php'));

        foreach ($allBundles as &$bundle) {
            $bundle['is_installed'] = !empty($bundle['provider_class'])
                && str_contains($providersContent, $bundle['provider_class']);
        }
        unset($bundle);

        $installed  = array_filter($allBundles, fn($b) => $b['is_installed']);
        $extracted  = array_filter($allBundles, fn($b) => !$b['is_installed']);

        return view('bundle-installer::bundle-installer.index', compact('installed', 'extracted'));
    }

    private function findBundles(string $basePath, string $prefix, array &$bundles): void
    {
        $items = array_diff(scandir($basePath), ['.', '..']);

        foreach ($items as $item) {
            $fullPath    = "$basePath/$item";
            $packagePath = $prefix ? "$prefix/$item" : $item;

            if (!is_dir($fullPath)) {
                continue;
            }

            $manifestPath = "$fullPath/manifest.json";
            if (file_exists($manifestPath)) {
                $manifest   = json_decode(file_get_contents($manifestPath), true) ?? [];
                $bundles[]  = [
                    'name'               => $manifest['name']               ?? $item,
                    'version'            => $manifest['version']            ?? '1.0.0',
                    'description'        => $manifest['description']        ?? '',
                    'author'             => $manifest['author']             ?? '',
                    'features'           => $manifest['features']           ?? [],
                    'provider_class'     => $manifest['provider_class']     ?? '',
                    'permissions_module' => $manifest['permissions_module'] ?? '',
                    'bundle_roles'       => $manifest['bundle_roles']       ?? [],
                    'main_route'         => $manifest['main_route']         ?? '',
                    'package'            => $packagePath,
                    'is_installed'       => false,
                ];
            } else {
                $this->findBundles($fullPath, $packagePath, $bundles);
            }
        }
    }

    public function upload(Request $request)
    {
        Log::info('Bundle upload request received', [
            'has_file' => $request->hasFile('bundle'),
        ]);

        try {
            $request->validate([
                'bundle' => 'required|file|mimes:zip|max:102400',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = $e->errors()['bundle'][0] ?? 'File validation failed';
            return back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }

        $file = $request->file('bundle');

        $disk       = \Illuminate\Support\Facades\Storage::disk('local');
        $storageDir = $disk->path('bundles/temp');
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0777, true);
        }

        $tempPath = $file->store('bundles/temp', 'local');
        $fullPath = $disk->path($tempPath);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'Failed to store uploaded file');
        }

        $validation = $this->extractor->validate($fullPath);
        if (!$validation['valid']) {
            @unlink($fullPath);
            return back()->with('error', $validation['error']);
        }

        $manifest    = $validation['manifest'];
        $packagePath = base_path("app/Packages/{$manifest['package_path']}");

        if (is_dir($packagePath)) {
            @unlink($fullPath);
            return back()->with('error', "Package {$manifest['package_path']} already exists. Uninstall it first.");
        }

        $extraction = $this->extractor->extract($fullPath, $manifest, $validation['prefix'] ?? '');
        @unlink($fullPath);

        if (!$extraction['success']) {
            return back()->with('error', $extraction['error']);
        }

        return back()->with('success', "{$manifest['name']} v{$manifest['version']} uploaded. Click Install to activate it.");
    }

    public function install(string $bundle)
    {
        $packagePath  = base_path("app/Packages/$bundle");

        if (!is_dir($packagePath)) {
            return back()->with('error', 'Bundle directory not found: ' . $bundle);
        }

        $manifestPath = "$packagePath/manifest.json";
        if (!file_exists($manifestPath)) {
            return back()->with('error', 'manifest.json not found in bundle: ' . $bundle);
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (!$manifest) {
            return back()->with('error', 'Invalid manifest.json format');
        }

        // Register PSR-4 and provider
        $registration = $this->registrar->register($manifest);
        if (!$registration['success']) {
            return back()->with('error', 'Registration failed: ' . $registration['error']);
        }

        // Run migrations
        $migrations = $this->registrar->runMigrations();
        if (!$migrations['success']) {
            return back()->with('error', 'Migration failed: ' . $migrations['error']);
        }

        $this->registrar->clearCache();

        if (!empty($manifest['setup_route'])) {
            return redirect($manifest['setup_route'])
                ->with('success', "{$manifest['name']} v{$manifest['version']} installed! Complete the setup below.");
        }

        return back()->with('success', "{$manifest['name']} v{$manifest['version']} installed successfully!");
    }

    public function removeFiles(string $bundle): RedirectResponse
    {
        $packagePath = base_path("app/Packages/$bundle");

        if (!is_dir($packagePath)) {
            return back()->with('error', 'Bundle not found');
        }

        $manifestPath = "$packagePath/manifest.json";
        $manifest     = [];
        $bundleName   = $bundle;

        if (file_exists($manifestPath)) {
            $manifest   = json_decode(file_get_contents($manifestPath), true) ?? [];
            $bundleName = $manifest['name'] ?? $bundle;
        }

        // Remove permissions and roles
        $this->removePermissionsAndRoles(
            $manifest['permissions_module'] ?? '',
            $manifest['bundle_roles']       ?? []
        );

        // Drop migration tables (read files before deleting directory)
        $this->dropBundleMigrations($packagePath);

        // Remove PSR-4 from composer.json + dump-autoload
        $this->registrar->unregisterPsr4Namespace($bundle);

        // Delete source files
        $this->removeDir($packagePath);

        return back()->with('success', "'{$bundleName}' removed — files, tables, permissions, and roles deleted.");
    }

    public function destroy(string $bundle): RedirectResponse
    {
        $packagePath = base_path("app/Packages/$bundle");

        if (!is_dir($packagePath)) {
            return back()->with('error', 'Bundle not found');
        }

        $manifestPath    = "$packagePath/manifest.json";
        $manifest        = [];
        $providerClass   = '';
        $bundleName      = $bundle;

        if (file_exists($manifestPath)) {
            $manifest      = json_decode(file_get_contents($manifestPath), true) ?? [];
            $providerClass = $manifest['provider_class'] ?? '';
            $bundleName    = $manifest['name'] ?? $bundle;
        }

        // Step 1 — Remove permissions and bundle-specific roles
        $this->removePermissionsAndRoles(
            $manifest['permissions_module'] ?? '',
            $manifest['bundle_roles']       ?? []
        );

        // Step 2 — Drop migration tables and remove migration records
        $this->dropBundleMigrations($packagePath);

        // Step 3 — Remove from bootstrap/providers.php
        if ($providerClass) {
            $this->removeFromProviders($providerClass);
        }

        // Step 4 — Clear all caches
        try {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception) {
        }

        return back()->with('success', "'{$bundleName}' uninstalled — tables, permissions, and roles removed. Source files are kept on disk.");
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    private function removePermissionsAndRoles(string $moduleName, array $bundleRoles): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        try {
            // Forget cached permissions first
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            if ($moduleName) {
                $permissions = \Spatie\Permission\Models\Permission::where('module_name', $moduleName)->get();
                foreach ($permissions as $permission) {
                    DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
                    DB::table('model_has_permissions')->where('permission_id', $permission->id)->delete();
                    $permission->delete();
                }
            }

            foreach ($bundleRoles as $roleName) {
                $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
                if ($role) {
                    DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
                    DB::table('model_has_roles')->where('role_id', $role->id)->delete();
                    $role->delete();
                }
            }

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        } catch (\Exception $e) {
            Log::warning('Could not remove permissions/roles: ' . $e->getMessage());
        }
    }

    private function dropBundleMigrations(string $packagePath): void
    {
        $migrationsPath = "$packagePath/src/Database/migrations";
        if (!is_dir($migrationsPath)) {
            return;
        }

        // Collect table names from migration files (in reverse order for FK safety)
        $files = array_reverse(glob("$migrationsPath/*.php") ?: []);

        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Extract Schema::create('table_name') patterns
            preg_match_all("/Schema::create\s*\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);

            foreach ($matches[1] as $table) {
                try {
                    Schema::dropIfExists($table);
                } catch (\Exception $e) {
                    Log::warning("Could not drop table '{$table}': " . $e->getMessage());
                }
            }

            // Remove the migration record from the migrations table
            if (Schema::hasTable('migrations')) {
                $migrationName = pathinfo($file, PATHINFO_FILENAME);
                try {
                    DB::table('migrations')->where('migration', $migrationName)->delete();
                } catch (\Exception) {
                }
            }
        }
    }

    private function removeFromProviders(string $providerClass): void
    {
        $providersFile = base_path('bootstrap/providers.php');
        if (!file_exists($providersFile)) {
            return;
        }

        $content         = file_get_contents($providersFile);
        $originalContent = $content;

        $parts     = explode('\\', $providerClass);
        $className = end($parts);

        foreach (["use {$providerClass};\n", "use {$providerClass};"] as $pattern) {
            if (str_contains($content, $pattern)) {
                $content = str_replace($pattern, '', $content);
                break;
            }
        }

        foreach ([
            "    {$className}::class,\n",
            "    {$className}::class,",
            "{$className}::class,\n",
            "{$className}::class,",
        ] as $pattern) {
            if (str_contains($content, $pattern)) {
                $content = str_replace($pattern, '', $content);
                break;
            }
        }

        if ($content !== $originalContent) {
            file_put_contents($providersFile, $content);
        }
    }

    private function removeDir(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $full = "$path/$file";
            if (is_dir($full)) {
                $this->removeDir($full);
            } else {
                @unlink($full);
            }
        }
        rmdir($path);
    }
}

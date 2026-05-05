<?php

namespace App\Packages\BundleInstaller\Controllers;

use App\Packages\BundleInstaller\Services\BundleExtractorService;
use App\Packages\BundleInstaller\Services\BundleRegistrarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
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
        $installedBundles = [];

        if (is_dir($packagesPath)) {
            $this->findBundles($packagesPath, '', $installedBundles);
        }

        return view('bundle-installer::bundle-installer.index', [
            'installedBundles' => $installedBundles,
        ]);
    }

    private function findBundles(string $basePath, string $prefix, array &$bundles): void
    {
        $items = array_diff(scandir($basePath), ['.', '..']);

        foreach ($items as $item) {
            $fullPath = "$basePath/$item";
            $packagePath = $prefix ? "$prefix/$item" : $item;

            if (is_dir($fullPath)) {
                $manifestPath = "$fullPath/manifest.json";
                if (file_exists($manifestPath)) {
                    $manifest = json_decode(file_get_contents($manifestPath), true);
                    $bundles[] = [
                        'name' => $manifest['name'] ?? $item,
                        'version' => $manifest['version'] ?? 'unknown',
                        'description' => $manifest['description'] ?? '',
                        'author' => $manifest['author'] ?? '',
                        'package' => $packagePath,
                    ];
                } else {
                    $this->findBundles($fullPath, $packagePath, $bundles);
                }
            }
        }
    }

    public function upload(Request $request)
    {
        Log::info('Bundle upload request received', [
            'has_file' => $request->hasFile('bundle'),
            'files' => $request->files->keys(),
        ]);

        try {
            $request->validate([
                'bundle' => 'required|file|mimes:zip|max:102400',
            ]);
            Log::info('File validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('File validation failed', ['errors' => $e->errors()]);
            $errorMsg = $e->errors()['bundle'][0] ?? 'File validation failed';
            return back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            Log::error('Unexpected upload error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }

        $file = $request->file('bundle');
        Log::info('File details', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ]);

        $disk = \Illuminate\Support\Facades\Storage::disk('local');

        // Ensure bundles/temp directory exists in the configured disk root
        $storageDir = $disk->path('bundles/temp');
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0777, true);
            Log::info('Created storage directory', ['path' => $storageDir]);
        }

        $tempPath = $file->store('bundles/temp', 'local');
        $fullPath = $disk->path($tempPath);

        Log::info('File stored', [
            'tempPath' => $tempPath,
            'fullPath' => $fullPath,
            'exists' => file_exists($fullPath),
        ]);

        if (!file_exists($fullPath)) {
            Log::error('Stored file not found', ['path' => $fullPath]);
            return back()->with('error', 'Failed to store uploaded file');
        }

        // Validate the bundle
        $validation = $this->extractor->validate($fullPath);

        if (!$validation['valid']) {
            @unlink($fullPath);
            return back()->with('error', $validation['error']);
        }

        $manifest = $validation['manifest'];

        // Check if package already exists
        $packagePath = base_path("app/Packages/{$manifest['package_path']}");
        if (is_dir($packagePath)) {
            @unlink($fullPath);
            return back()->with('error', "Package {$manifest['package_path']} already exists");
        }

        // Extract the bundle
        $extraction = $this->extractor->extract($fullPath, $manifest);
        @unlink($fullPath);

        if (!$extraction['success']) {
            return back()->with('error', $extraction['error']);
        }

        return back()->with('success', "{$manifest['name']} v{$manifest['version']} uploaded successfully");
    }

    public function install(Request $request, string $bundle)
    {
        $packagePath = base_path("app/Packages/$bundle");
        if (!is_dir($packagePath)) {
            return back()->with('error', 'Bundle not found');
        }

        $manifestPath = "$packagePath/manifest.json";
        if (!file_exists($manifestPath)) {
            return back()->with('error', 'manifest.json not found in bundle');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

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

        // Clear cache
        $this->registrar->clearCache();

        return back()->with('success', "{$manifest['name']} v{$manifest['version']} installed successfully!");
    }

    public function destroy(Request $request, string $bundle): RedirectResponse
    {
        $packagePath = base_path("app/Packages/$bundle");

        if (!is_dir($packagePath)) {
            return back()->with('error', 'Bundle not found');
        }

        // Step 1: Get manifest before deletion
        $manifestPath = "$packagePath/manifest.json";
        $providerClass = '';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $providerClass = $manifest['provider_class'] ?? '';
        }

        // Step 2: Remove from bootstrap/providers.php (with flexible formatting)
        if ($providerClass) {
            $this->removeFromProviders($providerClass);
        }

        // Step 3: Remove PSR-4 namespace FIRST (before deleting files)
        $this->registrar->unregisterPsr4Namespace($bundle);

        // Step 4: Clear view and route cache BEFORE deleting directory
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        // Step 5: Delete the package directory
        $this->removeDir($packagePath);

        // Step 6: Clear all caches
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return back()->with('success', 'Bundle removed successfully');
    }

    private function removeFromProviders(string $providerClass): void
    {
        $providersFile = base_path('bootstrap/providers.php');
        if (!file_exists($providersFile)) {
            return;
        }

        $content = file_get_contents($providersFile);
        $originalContent = $content;

        // Extract just the class name from the full namespace
        $parts = explode('\\', $providerClass);
        $className = end($parts);

        // Try to remove the use statement
        $usePatterns = [
            "use {$providerClass};\n",
            "use {$providerClass};",
        ];

        foreach ($usePatterns as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $content = str_replace($pattern, '', $content);
                break;
            }
        }

        // Try to remove from the return array - use class name patterns
        $returnPatterns = [
            "    {$className}::class,\n",
            "    {$className}::class,",
            "{$className}::class,\n",
            "{$className}::class,",
        ];

        foreach ($returnPatterns as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $content = str_replace($pattern, '', $content);
                break;
            }
        }

        // Only write if changes were made
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
            $file = "$path/$file";
            if (is_dir($file)) {
                $this->removeDir($file);
            } else {
                @unlink($file);
            }
        }
        rmdir($path);
    }
}

<?php

namespace App\Packages\BundleInstaller\Services;

use Illuminate\Support\Facades\Artisan;

class BundleRegistrarService
{
    public function register(array $manifest): array
    {
        // Step 1: Register PSR-4 namespace in composer.json
        $psr4Result = $this->registerPsr4Namespace($manifest);
        if (!$psr4Result['success']) {
            return $psr4Result;
        }

        // Step 2: Register provider in bootstrap/providers.php
        $providerResult = $this->registerProvider($manifest['provider_class']);
        if (!$providerResult['success']) {
            return $providerResult;
        }

        return ['success' => true];
    }

    private function registerPsr4Namespace(array $manifest): array
    {
        $composerFile = base_path('composer.json');

        if (!file_exists($composerFile)) {
            return ['success' => false, 'error' => 'composer.json not found'];
        }

        $composer = json_decode(file_get_contents($composerFile), true);

        if (!isset($composer['autoload']['psr-4'])) {
            $composer['autoload']['psr-4'] = [];
        }

        // Build namespace from package_path using naming convention
        // Naming convention: package_path like "Pro/DemoBundle" becomes "App\Packages\Pro\DemoBundle\"
        $packagePath = $manifest['package_path'];
        $pathParts = explode('/', $packagePath);

        // Convert path parts to PascalCase for namespace
        $namespaceParts = array_map(fn($part) => ucfirst($part), $pathParts);
        $namespace = 'App\\Packages\\' . implode('\\', $namespaceParts) . '\\';
        $path = 'app/Packages/' . $packagePath . '/src';

        // Already registered — nothing to do
        if (isset($composer['autoload']['psr-4'][$namespace])) {
            return ['success' => true];
        }

        // Add PSR-4 mapping in alphabetical order
        $psr4 = $composer['autoload']['psr-4'];
        $psr4[$namespace] = $path;
        ksort($psr4);
        $composer['autoload']['psr-4'] = $psr4;

        // Write back to composer.json
        $jsonContent = json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
        if (!file_put_contents($composerFile, $jsonContent)) {
            return ['success' => false, 'error' => 'Failed to write composer.json'];
        }

        // Run composer dump-autoload
        try {
            exec('cd ' . escapeshellarg(base_path()) . ' && composer dump-autoload 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                return ['success' => false, 'error' => 'Composer dump-autoload failed'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Composer error: ' . $e->getMessage()];
        }

        return ['success' => true];
    }

    private function registerProvider(string $providerClass): array
    {
        $providersFile = base_path('bootstrap/providers.php');

        if (!file_exists($providersFile)) {
            return ['success' => false, 'error' => 'bootstrap/providers.php not found'];
        }

        $content = file_get_contents($providersFile);

        // Already registered — nothing to do
        if (strpos($content, $providerClass) !== false) {
            return ['success' => true];
        }

        // Extract class name
        $parts = explode('\\', $providerClass);
        $className = end($parts);
        $useStatement = "use {$providerClass};";

        // STEP 1: Add use statement in alphabetical order among package imports
        // Find the last "use App\Packages\" import and add after it
        $lines = explode("\n", $content);
        $output = [];
        $useAdded = false;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            if (!$useAdded && strpos($line, 'use App\Packages\\') !== false) {
                $nextLine = $lines[$i + 1] ?? '';

                // Check if next line is also a package import
                if (strpos($nextLine, 'use App\Packages\\') === false && strpos($nextLine, 'use App\\Providers\\') !== false) {
                    // We've found the last package import, add here
                    $output[] = $line;
                    $output[] = $useStatement;
                    $useAdded = true;
                    $i++;
                    continue;
                } elseif (strpos($nextLine, 'use App\Packages\\') === false) {
                    // Next line is not a package import, add the use statement
                    $output[] = $line;
                    $output[] = $useStatement;
                    $useAdded = true;
                    continue;
                }
            }

            $output[] = $line;
        }

        // Fallback: if not added yet, add before AppServiceProvider
        if (!$useAdded) {
            $finalOutput = [];
            foreach ($output as $line) {
                if (strpos($line, 'use App\Providers\AppServiceProvider;') !== false) {
                    $finalOutput[] = $useStatement;
                }
                $finalOutput[] = $line;
            }
            $output = $finalOutput;
        }

        // STEP 2: Add provider to return array in alphabetical order
        $content = implode("\n", $output);
        $lines = explode("\n", $content);
        $output = [];
        $inProviderArray = false;
        $providerAdded = false;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            // Detect if we're in the provider array
            if (strpos($line, 'return [') !== false) {
                $inProviderArray = true;
            }

            // Add provider in correct position (before AppServiceProvider, but after other packages)
            if (!$providerAdded && $inProviderArray && strpos($line, 'AppServiceProvider::class,') !== false) {
                $output[] = "    {$className}::class,";
                $providerAdded = true;
            }

            $output[] = $line;
        }

        // Fallback: add at the end if not found
        if (!$providerAdded) {
            array_splice($output, -2, 0, ["    {$className}::class,"]);
        }

        $content = implode("\n", $output);

        if (!file_put_contents($providersFile, $content)) {
            return ['success' => false, 'error' => 'Failed to write bootstrap/providers.php'];
        }

        return ['success' => true];
    }

    public function runMigrations(): array
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function clearCache(): array
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function unregisterPsr4Namespace(string $packagePath): array
    {
        $composerFile = base_path('composer.json');

        if (!file_exists($composerFile)) {
            return ['success' => false, 'error' => 'composer.json not found'];
        }

        $composer = json_decode(file_get_contents($composerFile), true);

        if (!isset($composer['autoload']['psr-4'])) {
            return ['success' => true];
        }

        // Build the namespace to remove
        $namespace = 'App\\Packages\\' . str_replace('/', '\\', $packagePath) . '\\';

        // Remove from PSR-4 mapping
        if (isset($composer['autoload']['psr-4'][$namespace])) {
            unset($composer['autoload']['psr-4'][$namespace]);
        }

        // Write back
        $jsonContent = json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
        if (!file_put_contents($composerFile, $jsonContent)) {
            return ['success' => false, 'error' => 'Failed to update composer.json'];
        }

        // Run composer dump-autoload
        try {
            exec('cd ' . escapeshellarg(base_path()) . ' && composer dump-autoload 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                return ['success' => false, 'error' => 'Failed to dump autoload'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Composer error: ' . $e->getMessage()];
        }

        return ['success' => true];
    }
}

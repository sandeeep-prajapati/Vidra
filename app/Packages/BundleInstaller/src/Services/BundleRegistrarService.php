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

        // Get namespace from package_path in manifest
        $packagePath = $manifest['package_path'];
        $namespace = 'App\\Packages\\' . str_replace('/', '\\', $packagePath) . '\\';
        $path = 'app/Packages/' . $packagePath . '/src';

        // Check if already exists
        if (isset($composer['autoload']['psr-4'][$namespace])) {
            return ['success' => false, 'error' => 'PSR-4 namespace already registered'];
        }

        // Add PSR-4 mapping after Webhook entry (if exists) or at the end
        $psr4 = &$composer['autoload']['psr-4'];

        // Try to add after Webhook if it exists
        $newPsr4 = [];
        foreach ($psr4 as $key => $value) {
            $newPsr4[$key] = $value;
            if (strpos($key, 'Webhook') !== false) {
                $newPsr4[$namespace] = $path;
            }
        }

        // If Webhook wasn't found, just add at the end (before Database)
        if (!isset($newPsr4[$namespace])) {
            $newPsr4[$namespace] = $path;
        }

        $composer['autoload']['psr-4'] = $newPsr4;

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

        // Check if already registered
        if (strpos($content, $providerClass) !== false) {
            return ['success' => false, 'error' => 'Provider already registered'];
        }

        // Extract class name
        $parts = explode('\\', $providerClass);
        $className = end($parts);
        $useStatement = "use {$providerClass};";

        // STEP 1: Add use statement after WebhookServiceProvider or before AppServiceProvider
        if (strpos($content, 'use App\Packages\Webhook\Providers\WebhookServiceProvider;') !== false) {
            $content = str_replace(
                'use App\Packages\Webhook\Providers\WebhookServiceProvider;',
                "use App\Packages\Webhook\Providers\WebhookServiceProvider;\n{$useStatement}",
                $content
            );
        } else {
            $content = str_replace(
                'use App\Providers\AppServiceProvider;',
                "{$useStatement}\nuse App\Providers\AppServiceProvider;",
                $content
            );
        }

        // STEP 2: Add provider entry after WebhookServiceProvider in return array
        $lines = explode("\n", $content);
        $output = [];
        $added = false;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            $output[] = $line;

            // Add after WebhookServiceProvider if found
            if (!$added && strpos($line, 'WebhookServiceProvider::class,') !== false) {
                $output[] = "    {$className}::class,";
                $added = true;
            }
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

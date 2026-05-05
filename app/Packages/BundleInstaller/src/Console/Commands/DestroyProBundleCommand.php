<?php

namespace App\Packages\BundleInstaller\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DestroyProBundleCommand extends Command
{
    protected $signature = 'bundle:destroy-pro
        {name : Bundle name (e.g., StudentPremium)}
        {--force : Skip confirmation}';

    protected $description = 'Remove a premium bundle and clean up all registrations';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $bundleName = $this->argument('name');
        $force = $this->option('force');

        // Validate bundle name
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $bundleName)) {
            $this->error('Bundle name must start with uppercase and contain only alphanumeric characters.');
            return self::FAILURE;
        }

        $packagePath = "Pro/{$bundleName}";
        $bundlePath = base_path("app/Packages/{$packagePath}");

        // Check if bundle exists
        if (!$this->files->isDirectory($bundlePath)) {
            $this->error("Bundle '{$bundleName}' not found at {$packagePath}");
            return self::FAILURE;
        }

        // Confirm deletion
        if (!$force && !$this->confirm("Are you sure you want to delete the '{$bundleName}' bundle? This will remove all files and provider registrations.")) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        $this->info("Removing premium bundle: {$bundleName}...\n");

        try {
            // Remove bundle directory
            $this->files->deleteDirectory($bundlePath);
            $this->line('✓ Bundle directory removed');

            // Remove from bootstrap/providers.php
            $this->removeFromProviders($bundleName, $packagePath);
            $this->line('✓ Service provider removed from bootstrap/providers.php');

            // Remove from composer.json PSR-4
            $this->removeFromComposerJson($packagePath);
            $this->line('✓ PSR-4 namespace removed from composer.json');

            $this->newLine();
            $this->info("✨ Bundle '{$bundleName}' removed successfully!");
            $this->newLine();
            $this->info('📋 Next Steps:');
            $this->line('1. Run: php artisan cache:clear');
            $this->line('2. Run: composer dump-autoload');
            $this->line('3. Delete any database records or migrations if needed');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error removing bundle: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function removeFromProviders(string $bundleName, string $packagePath): void
    {
        $bootstrapPath = base_path('bootstrap/providers.php');

        if (!$this->files->exists($bootstrapPath)) {
            return;
        }

        $content = $this->files->get($bootstrapPath);

        // Remove the provider class registration
        $namespace = $this->getNamespaceFromPackagePath($packagePath, $bundleName);
        $providerClass = "{$namespace}\\Providers\\{$bundleName}ServiceProvider::class,";

        $content = str_replace(
            "    {$providerClass}\n",
            '',
            $content
        );

        $this->files->put($bootstrapPath, $content);
    }

    private function removeFromComposerJson(string $packagePath): void
    {
        $composerPath = base_path('composer.json');

        if (!$this->files->exists($composerPath)) {
            return;
        }

        $composer = json_decode($this->files->get($composerPath), true);

        // Remove from PSR-4 autoload
        if (isset($composer['autoload']['psr-4'])) {
            $namespace = $this->getNamespaceFromPackagePath($packagePath);
            unset($composer['autoload']['psr-4'][$namespace]);

            // Sort PSR-4 entries alphabetically
            if (!empty($composer['autoload']['psr-4'])) {
                ksort($composer['autoload']['psr-4']);
            }
        }

        $this->files->put(
            $composerPath,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );
    }

    private function getNamespaceFromPackagePath(string $packagePath, string $bundleName = ''): string
    {
        $parts = array_map(fn($part) => ucfirst($part), explode('/', $packagePath));
        return 'App\\Packages\\' . implode('\\', $parts);
    }
}

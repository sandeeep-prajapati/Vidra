<?php

namespace App\Packages\BundleInstaller\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateBundleCommand extends Command
{
    protected $signature = 'bundle:create {name} {--version=1.0.0} {--author="Your Company"} {--description="A custom bundle"} {--pro : Create as premium bundle (Pro/)} {--with-menu}';
    protected $description = 'Create a new bundle from template with proper naming convention';

    public function handle()
    {
        $name = $this->argument('name');
        $version = $this->option('version');
        $author = $this->option('author');
        $description = $this->option('description');
        $isPro = $this->option('pro');
        $withMenu = $this->option('with-menu');

        $bundleName = Str::studly($name);
        $packagePath = $isPro ? "Pro/{$bundleName}" : $bundleName;
        $kebab = Str::kebab($name);
        $snake = Str::snake($name);

        $this->info("Creating bundle: {$bundleName} v{$version} ({$packagePath})");

        // Validate bundle name
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $bundleName)) {
            $this->error('Bundle name must start with uppercase and contain only alphanumeric characters.');
            return 1;
        }

        // Create directory structure
        $baseDir = base_path("app/Packages/{$packagePath}");

        if (is_dir($baseDir)) {
            $this->error("Bundle already exists at: {$packagePath}");
            return 1;
        }

        $this->createDirectories($baseDir);

        // Create files
        $this->createManifest($baseDir, $bundleName, $packagePath, $version, $author, $description);
        $this->createServiceProvider($baseDir, $packagePath, $bundleName, $kebab);
        $this->createController($baseDir, $packagePath, $bundleName, $kebab);
        $this->createModel($baseDir, $packagePath, $bundleName, $snake);
        $this->createMigration($baseDir, $packagePath, $bundleName, $snake);
        $this->createRoutes($baseDir, $packagePath, $bundleName, $kebab);
        $this->createView($baseDir, $bundleName);

        if ($withMenu) {
            $this->createMenuProvider($baseDir, $packagePath, $bundleName, $kebab);
            $this->info("✓ Menu provider created");
        }

        $this->createReadme($baseDir, $bundleName, $packagePath, $version, $description);

        $this->info("\n✅ Bundle created successfully!");
        $this->info("\nTo zip the bundle:");
        $zipPath = $isPro ? "Pro" : ".";
        $this->info("  cd app/Packages/{$zipPath}");
        $this->info("  zip -r {$bundleName}.zip {$bundleName}/");
        $this->info("\nTo install via Bundle Installer:");
        $this->info("  1. Go to /bundle-installer");
        $this->info("  2. Upload the ZIP file");
        $this->info("  3. System will auto-register PSR-4 and provider");
    }

    private function createDirectories($baseDir)
    {
        $dirs = [
            "{$baseDir}/src/Providers",
            "{$baseDir}/src/Controllers",
            "{$baseDir}/src/Models",
            "{$baseDir}/src/Database/migrations",
            "{$baseDir}/src/Routes",
            "{$baseDir}/src/Views",
            "{$baseDir}/src/Assets",
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
    }

    private function createManifest($baseDir, $bundleName, $packagePath, $version, $author, $description)
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $manifest = [
            'name' => $bundleName,
            'version' => $version,
            'description' => $description,
            'author' => $author,
            'package_path' => $packagePath,
            'provider_class' => "App\\Packages\\{$providerNamespace}\\Providers\\{$bundleName}ServiceProvider",
        ];

        file_put_contents(
            "{$baseDir}/manifest.json",
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );

        $this->line("✓ Created manifest.json");
    }

    private function createServiceProvider($baseDir, $packagePath, $bundleName, $kebab)
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $content = <<<PHP
<?php

namespace App\Packages\\{$providerNamespace}\Providers;

use Illuminate\Support\ServiceProvider;

class {$bundleName}ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \$this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        \$this->loadViewsFrom(__DIR__.'/../Views', '{$kebab}');
        \$this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }
}
PHP;

        file_put_contents("{$baseDir}/src/Providers/{$bundleName}ServiceProvider.php", $content);
        $this->line("✓ Created ServiceProvider");
    }

    private function createController($baseDir, $packagePath, $bundleName, $kebab)
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $content = <<<PHP
<?php

namespace App\Packages\\{$providerNamespace}\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class {$bundleName}Controller extends BaseController
{
    public function index(): View
    {
        return view('{$kebab}::index', [
            'title' => '{$bundleName} Module',
            'message' => 'Welcome to {$bundleName}!',
        ]);
    }
}
PHP;

        file_put_contents("{$baseDir}/src/Controllers/{$bundleName}Controller.php", $content);
        $this->line("✓ Created Controller");
    }

    private function createModel($baseDir, $packagePath, $bundleName, $snake)
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $modelName = Str::studly(Str::singular($snake));
        $content = <<<PHP
<?php

namespace App\Packages\\{$providerNamespace}\Models;

use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    protected \$table = '{$snake}';
    protected \$fillable = ['name', 'description'];
    public \$timestamps = true;
}
PHP;

        file_put_contents("{$baseDir}/src/Models/{$modelName}.php", $content);
        $this->line("✓ Created Model");
    }

    private function createMigration($baseDir, $packagePath, $bundleName, $snake)
    {
        $timestamp = now()->format('YmdHis');
        $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('{$snake}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$snake}');
    }
};
PHP;

        file_put_contents("{$baseDir}/src/Database/migrations/{$timestamp}_create_{$snake}_table.php", $content);
        $this->line("✓ Created Migration");
    }

    private function createRoutes($baseDir, $packagePath, $bundleName, $kebab)
    {
        $controllerName = $bundleName . 'Controller';
        $content = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Packages\\{namespace}\Controllers\\{$controllerName};

Route::prefix('{$kebab}')->group(function () {
    Route::get('/', [{$controllerName}::class, 'index'])->name('{$kebab}.index');
});
PHP;

        file_put_contents("{$baseDir}/src/Routes/web.php", $content);
        $this->line("✓ Created Routes");
    }

    private function createView($baseDir, $bundleName)
    {
        $content = <<<'BLADE'
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8">
            <h1 class="text-4xl font-bold text-blue-900 mb-4">{{ $title }}</h1>
            <p class="text-xl text-blue-700">{{ $message }}</p>
        </div>
    </div>
</div>
BLADE;

        file_put_contents("{$baseDir}/src/Views/index.blade.php", $content);
        $this->line("✓ Created View");
    }

    private function createMenuProvider($baseDir, $packagePath, $bundleName, $kebab)
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $content = <<<PHP
<?php

namespace App\Packages\\{$providerNamespace}\Providers;

use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    public static function getMenuItems(): array
    {
        return [
            [
                'label' => '{$bundleName}',
                'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3',
                'route' => '{$kebab}.index',
                'active' => request()->routeIs('{$kebab}.*'),
            ],
        ];
    }
}
PHP;

        file_put_contents("{$baseDir}/src/Providers/MenuProvider.php", $content);
        $this->line("✓ Created MenuProvider");
    }

    private function createReadme($baseDir, $bundleName, $packagePath, $version, $description)
    {
        $content = <<<MARKDOWN
# {$bundleName} Bundle v{$version}

{$description}

## Installation

Upload this bundle via the Bundle Installer at `/bundle-installer` in your application.

The system will automatically:
- Register PSR-4 autoloading namespace
- Register service provider
- Load routes, views, and migrations

## Structure

- `manifest.json` - Bundle metadata
- `src/Providers/` - Service provider
- `src/Controllers/` - Application controllers
- `src/Models/` - Eloquent models
- `src/Routes/` - Route definitions
- `src/Views/` - Blade views
- `src/Database/migrations/` - Database migrations

## Features

- Sample model and migration
- Sample controller and route
- Blade view template
- Proper PSR-4 namespace structure (App\\Packages\\{$packagePath}\\)

## Quick Reference

For naming conventions and detailed documentation, see:
- `BUNDLE_NAMING_CONVENTION.md` - Complete naming guide
- `QUICK_START.md` - Quick reference guide
MARKDOWN;

        file_put_contents("{$baseDir}/README.md", $content);
        $this->line("✓ Created README.md");
    }
}

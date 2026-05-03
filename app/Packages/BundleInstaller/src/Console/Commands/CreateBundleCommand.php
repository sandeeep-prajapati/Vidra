<?php

namespace App\Packages\BundleInstaller\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateBundleCommand extends Command
{
    protected $signature = 'bundle:create {name} {--version=1.0.0} {--author="} {--description="} {--with-menu}';
    protected $description = 'Create a new bundle scaffold with all required files';

    public function handle()
    {
        $name = $this->argument('name');
        $version = $this->option('version');
        $author = $this->option('author') ?: 'Developer';
        $description = $this->option('description') ?: "A custom {$name} module";
        $withMenu = $this->option('with-menu');

        $namespace = Str::studly($name);
        $kebab = Str::kebab($name);
        $snake = Str::snake($name);

        $this->info("Creating bundle: {$namespace} v{$version}");

        // Create directory structure
        $baseDir = base_path("app/Packages/{$namespace}");
        $this->createDirectories($baseDir);

        // Create files
        $this->createManifest($baseDir, $namespace, $version, $author, $description);
        $this->createServiceProvider($baseDir, $namespace, $kebab);
        $this->createController($baseDir, $namespace, $kebab);
        $this->createModel($baseDir, $namespace, $snake);
        $this->createMigration($baseDir, $namespace, $snake);
        $this->createRoutes($baseDir, $namespace, $kebab);
        $this->createView($baseDir, $namespace);

        if ($withMenu) {
            $this->createMenuProvider($baseDir, $namespace, $kebab);
            $this->info("✓ Menu provider created - bundles can now inject nav items");
        }

        $this->createReadme($baseDir, $namespace, $version, $description);

        $this->info("\n✅ Bundle created successfully!");
        $this->info("\nTo zip the bundle:");
        $this->info("  zip -r {$namespace}-v{$version}.zip app/Packages/{$namespace}");
        $this->info("\nTo install via Bundle Installer:");
        $this->info("  1. Go to /bundle-installer");
        $this->info("  2. Upload the ZIP file");
        $this->info("  3. Click 'Install Now'");
    }

    private function createDirectories($baseDir)
    {
        $dirs = [
            "{$baseDir}/src/Providers",
            "{$baseDir}/src/Controllers",
            "{$baseDir}/src/Models",
            "{$baseDir}/src/Database/migrations",
            "{$baseDir}/src/Routes",
            "{$baseDir}/src/Resources/views",
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    private function createManifest($baseDir, $namespace, $version, $author, $description)
    {
        $manifest = [
            'name' => $namespace,
            'version' => $version,
            'description' => $description,
            'author' => $author,
            'provider_class' => "App\\Packages\\{$namespace}\\Providers\\{$namespace}ServiceProvider",
            'package_path' => $namespace,
        ];

        file_put_contents(
            "{$baseDir}/manifest.json",
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $this->line("✓ Created manifest.json");
    }

    private function createServiceProvider($baseDir, $namespace, $kebab)
    {
        $content = <<<PHP
<?php

namespace App\Packages\\{$namespace}\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class {$namespace}ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \$this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        \$this->loadViewsFrom(__DIR__ . '/../Resources/views', '{$kebab}');
        \$this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->namespace('App\Packages\\{$namespace}\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');
    }
}
PHP;

        file_put_contents("{$baseDir}/src/Providers/{$namespace}ServiceProvider.php", $content);
        $this->line("✓ Created ServiceProvider");
    }

    private function createController($baseDir, $namespace, $kebab)
    {
        $content = <<<PHP
<?php

namespace App\Packages\\{$namespace}\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function index(): View
    {
        return view('{$kebab}::index', [
            'title' => '{$namespace} Module',
            'message' => 'Welcome to {$namespace}!',
        ]);
    }
}
PHP;

        file_put_contents("{$baseDir}/src/Controllers/DemoController.php", $content);
        $this->line("✓ Created Controller");
    }

    private function createModel($baseDir, $namespace, $snake)
    {
        $content = <<<PHP
<?php

namespace App\Packages\\{$namespace}\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    protected \$table = '{$snake}_demos';
    protected \$fillable = ['name', 'description'];
    public \$timestamps = true;
}
PHP;

        file_put_contents("{$baseDir}/src/Models/Demo.php", $content);
        $this->line("✓ Created Model");
    }

    private function createMigration($baseDir, $namespace, $snake)
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
        Schema::create('{$snake}_demos', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$snake}_demos');
    }
};
PHP;

        file_put_contents("{$baseDir}/src/Database/migrations/{$timestamp}_create_{$snake}_demos_table.php", $content);
        $this->line("✓ Created Migration");
    }

    private function createRoutes($baseDir, $namespace, $kebab)
    {
        $content = <<<PHP
<?php

use Illuminate\Support\Facades\Route;

Route::get('/{$kebab}', ['DemoController', 'index'])->name('{$kebab}.index');
PHP;

        file_put_contents("{$baseDir}/src/Routes/web.php", $content);
        $this->line("✓ Created Routes");
    }

    private function createView($baseDir, $namespace)
    {
        $content = <<<'BLADE'
@extends('core-package::layouts.app')

@section('breadcrumb')
    <nav style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;">
        <a href="/" style="color:#0ea5e9;">Home</a>
        <span style="color:#cbd5e1;">/</span>
        <span style="color:#475569;">{{ $title }}</span>
    </nav>
@endsection

@section('content')
    <div style="padding:1.5rem;max-width:64rem;margin:0 auto;">
        <x-core-package::card title="{{ $title }}" subtitle="Custom module from bundle">
            <p style="color:#475569;margin:0;">
                {{ $message }}
            </p>
        </x-core-package::card>
    </div>
@endsection
BLADE;

        file_put_contents("{$baseDir}/src/Resources/views/index.blade.php", $content);
        $this->line("✓ Created View");
    }

    private function createMenuProvider($baseDir, $namespace, $kebab)
    {
        $content = <<<PHP
<?php

namespace App\Packages\\{$namespace}\Providers;

use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    /**
     * Register menu items for this bundle
     *
     * Usage in app.blade.php sidebar:
     * @php
     *     \$menuItems = \\App\\Packages\\{$namespace}\\Providers\\MenuProvider::getMenuItems();
     * @endphp
     */
    public static function getMenuItems(): array
    {
        return [
            [
                'label' => '{$namespace}',
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

    private function createReadme($baseDir, $namespace, $version, $description)
    {
        $content = <<<MARKDOWN
# {$namespace} Bundle v{$version}

{$description}

## Installation

Upload this bundle via the Bundle Installer at `/bundle-installer` in your application.

## Usage

After installation, navigate to `/{$namespace}` to access the module.

## Features

- Sample model and migration
- Sample controller and route
- Blade view with CorePackage styling
- Proper PSR-4 namespace structure

## Menu Integration

To add this bundle to the main navigation, see BUNDLE_CREATION_GUIDE.md
MARKDOWN;

        file_put_contents("{$baseDir}/README.md", $content);
        $this->line("✓ Created README.md");
    }
}

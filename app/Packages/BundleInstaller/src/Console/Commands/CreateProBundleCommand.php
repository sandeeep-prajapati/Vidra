<?php

namespace App\Packages\BundleInstaller\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CreateProBundleCommand extends Command
{
    protected $signature = 'bundle:create-pro
        {name : Bundle name (e.g., StudentPremium)}
        {--author=Your Company : Bundle author}
        {--description=A premium bundle : Bundle description}
        {--version=1.0.0 : Bundle version}
        {--with-permissions : Create permissions}
        {--with-menu : Create menu provider}
        {--with-models : Create sample model}';

    protected $description = 'Create a complete premium bundle with permissions and menu injection';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $bundleName = $this->argument('name');
        $author = $this->option('author');
        $description = $this->option('description');
        $version = $this->option('version');
        $withPermissions = $this->option('with-permissions') ?: true;
        $withMenu = $this->option('with-menu') ?: true;
        $withModels = $this->option('with-models') ?: false;

        // Validate bundle name
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $bundleName)) {
            $this->error('Bundle name must start with uppercase and contain only alphanumeric characters.');
            return self::FAILURE;
        }

        $packagePath = "Pro/{$bundleName}";
        $bundlePath = base_path("app/Packages/{$packagePath}");

        // Check if bundle already exists
        if ($this->files->isDirectory($bundlePath)) {
            $this->error("Bundle '{$bundleName}' already exists at {$packagePath}");
            return self::FAILURE;
        }

        $this->info("Creating premium bundle: {$bundleName}...\n");

        try {
            // Create directories
            $this->createDirectories($bundlePath);
            $this->line('✓ Directory structure created');

            // Create manifest
            $this->createManifest($bundlePath, $bundleName, $packagePath, $version, $author, $description);
            $this->line('✓ manifest.json created');

            // Create service provider
            $this->createServiceProvider($bundlePath, $packagePath, $bundleName);
            $this->line('✓ Service provider created');

            // Create menu provider
            if ($withMenu) {
                $this->createMenuProvider($bundlePath, $bundleName);
                $this->line('✓ Menu provider created');
            }

            // Create routes
            $this->createRoutes($bundlePath, $packagePath, $bundleName);
            $this->line('✓ Routes created');

            // Create controller
            $this->createController($bundlePath, $packagePath, $bundleName);
            $this->line('✓ Controller created');

            // Create model
            if ($withModels) {
                $this->createModel($bundlePath, $packagePath, $bundleName);
                $this->line('✓ Model created');
            }

            // Create views
            $this->createViews($bundlePath, $bundleName);
            $this->line('✓ Views created');

            // Create migration
            $this->createMigration($bundlePath, $bundleName);
            $this->line('✓ Migration created');

            // Create README
            $this->createReadme($bundlePath, $bundleName, $packagePath, $description);
            $this->line('✓ README created');

            $this->newLine();
            $this->info("✨ Premium bundle '{$bundleName}' created successfully!\n");

            $this->showNextSteps($packagePath, $bundleName);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create bundle: {$e->getMessage()}");
            if ($this->files->isDirectory($bundlePath)) {
                $this->files->deleteDirectory($bundlePath);
            }
            return self::FAILURE;
        }
    }

    private function createDirectories(string $bundlePath): void
    {
        $dirs = [
            "{$bundlePath}/src/Providers",
            "{$bundlePath}/src/Controllers",
            "{$bundlePath}/src/Models",
            "{$bundlePath}/src/Database/migrations",
            "{$bundlePath}/src/Routes",
            "{$bundlePath}/src/Views",
            "{$bundlePath}/src/Assets",
        ];

        foreach ($dirs as $dir) {
            @mkdir($dir, 0755, true);
        }
    }

    private function createManifest(string $bundlePath, string $bundleName, string $packagePath, string $version, string $author, string $description): void
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $manifest = [
            'name' => $bundleName,
            'version' => $version,
            'description' => $description,
            'author' => $author,
            'license' => 'MIT',
            'package_path' => $packagePath,
            'provider_class' => "App\\Packages\\{$providerNamespace}\\Providers\\{$bundleName}ServiceProvider",
        ];

        $this->files->put(
            "{$bundlePath}/manifest.json",
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );
    }

    private function createServiceProvider(string $bundlePath, string $packagePath, string $bundleName): void
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $kebab = Str::kebab($bundleName);

        $content = <<<'PHP'
<?php

namespace {NAMESPACE}\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class {BUNDLE_NAME}ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register permissions
        $this->registerPermissions();

        // Load assets
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', '{KEBAB}');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_{KEBAB}',
            'create_{KEBAB}_item',
            'edit_{KEBAB}_item',
            'delete_{KEBAB}_item',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Assign to roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);

        $admin->syncPermissions($permissions);
        $teacher->syncPermissions(['view_{KEBAB}', 'edit_{KEBAB}_item']);
    }
}
PHP;

        $content = str_replace([
            '{NAMESPACE}',
            '{BUNDLE_NAME}',
            '{KEBAB}',
        ], [
            "App\\Packages\\{$providerNamespace}",
            $bundleName,
            $kebab,
        ], $content);

        $this->files->put("{$bundlePath}/src/Providers/{$bundleName}ServiceProvider.php", $content);
    }

    private function createMenuProvider(string $bundlePath, string $bundleName): void
    {
        $kebab = Str::kebab($bundleName);

        $content = <<<'PHP'
<?php

namespace {NAMESPACE}\Providers;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $items = [];

        // Main menu item
        if (auth()->user()->can('view_{KEBAB}')) {
            $items[] = [
                'label' => '{BUNDLE_NAME}',
                'route' => '{KEBAB}.index',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/></svg>',
                'active' => request()->routeIs('{KEBAB}.*'),
                'permission' => 'view_{KEBAB}',
            ];
        }

        // Admin submenu
        if (auth()->user()->hasRole('admin')) {
            $items[] = ['type' => 'divider'];
            $items[] = [
                'label' => '{BUNDLE_NAME} Admin',
                'submenu' => [
                    [
                        'label' => 'Settings',
                        'route' => '{KEBAB}.settings',
                        'active' => request()->routeIs('{KEBAB}.settings'),
                    ],
                    [
                        'label' => 'Export Data',
                        'route' => '{KEBAB}.export',
                        'active' => request()->routeIs('{KEBAB}.export'),
                    ],
                ],
            ];
        }

        return $items;
    }
}
PHP;

        $content = str_replace([
            '{NAMESPACE}',
            '{BUNDLE_NAME}',
            '{KEBAB}',
        ], [
            "App\\Packages\\Pro\\{$bundleName}\\Providers",
            $bundleName,
            $kebab,
        ], $content);

        $this->files->put("{$bundlePath}/src/Providers/MenuProvider.php", $content);
    }

    private function createRoutes(string $bundlePath, string $packagePath, string $bundleName): void
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $kebab = Str::kebab($bundleName);

        $content = <<<'PHP'
<?php

use App\Packages\{NAMESPACE}\Controllers\{BUNDLE_NAME}Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('{KEBAB}')->group(function () {
        Route::get('/', [{BUNDLE_NAME}Controller::class, 'index'])
            ->middleware('permission:view_{KEBAB}')
            ->name('{KEBAB}.index');

        Route::post('/', [{BUNDLE_NAME}Controller::class, 'store'])
            ->middleware('permission:create_{KEBAB}_item')
            ->name('{KEBAB}.store');

        Route::get('/{id}/edit', [{BUNDLE_NAME}Controller::class, 'edit'])
            ->middleware('permission:edit_{KEBAB}_item')
            ->name('{KEBAB}.edit');

        Route::put('/{id}', [{BUNDLE_NAME}Controller::class, 'update'])
            ->middleware('permission:edit_{KEBAB}_item')
            ->name('{KEBAB}.update');

        Route::delete('/{id}', [{BUNDLE_NAME}Controller::class, 'destroy'])
            ->middleware('permission:delete_{KEBAB}_item')
            ->name('{KEBAB}.destroy');
    });
});
PHP;

        $content = str_replace([
            '{NAMESPACE}',
            '{BUNDLE_NAME}',
            '{KEBAB}',
        ], [
            $providerNamespace . '\\Controllers',
            $bundleName,
            $kebab,
        ], $content);

        $this->files->put("{$bundlePath}/src/Routes/web.php", $content);
    }

    private function createController(string $bundlePath, string $packagePath, string $bundleName): void
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $kebab = Str::kebab($bundleName);

        $content = <<<'PHP'
<?php

namespace App\Packages\{NAMESPACE}\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class {BUNDLE_NAME}Controller extends BaseController
{
    public function index(): View
    {
        if (!auth()->user()->can('view_{KEBAB}')) {
            abort(403, 'Unauthorized');
        }

        return view('{KEBAB}::index', [
            'title' => '{BUNDLE_NAME}',
            'message' => 'Welcome to {BUNDLE_NAME}!',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->can('create_{KEBAB}_item')) {
            abort(403);
        }

        // Your store logic here

        return redirect()->route('{KEBAB}.index')
            ->with('success', 'Item created successfully');
    }

    public function edit(Request $request, $id): View
    {
        if (!auth()->user()->can('edit_{KEBAB}_item')) {
            abort(403);
        }

        return view('{KEBAB}::edit', [
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('edit_{KEBAB}_item')) {
            abort(403);
        }

        // Your update logic here

        return redirect()->route('{KEBAB}.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('delete_{KEBAB}_item')) {
            abort(403);
        }

        // Your delete logic here

        return redirect()->route('{KEBAB}.index')
            ->with('success', 'Item deleted successfully');
    }
}
PHP;

        $content = str_replace([
            '{NAMESPACE}',
            '{BUNDLE_NAME}',
            '{KEBAB}',
        ], [
            $providerNamespace . '\\Controllers',
            $bundleName,
            $kebab,
        ], $content);

        $this->files->put("{$bundlePath}/src/Controllers/{$bundleName}Controller.php", $content);
    }

    private function createModel(string $bundlePath, string $packagePath, string $bundleName): void
    {
        $providerNamespace = str_replace('/', '\\', $packagePath);
        $modelName = Str::singular($bundleName);
        $tableName = Str::snake(Str::plural($bundleName));

        $content = <<<'PHP'
<?php

namespace App\Packages\{NAMESPACE}\Models;

use Illuminate\Database\Eloquent\Model;

class {MODEL_NAME} extends Model
{
    protected $table = '{TABLE_NAME}';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
PHP;

        $content = str_replace([
            '{NAMESPACE}',
            '{MODEL_NAME}',
            '{TABLE_NAME}',
        ], [
            $providerNamespace . '\\Models',
            $modelName,
            $tableName,
        ], $content);

        $this->files->put("{$bundlePath}/src/Models/{$modelName}.php", $content);
    }

    private function createViews(string $bundlePath, string $bundleName): void
    {
        $kebab = Str::kebab($bundleName);

        // Index view
        $indexView = <<<'BLADE'
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">{{ $title }}</h1>
        <p class="text-gray-600 mb-8">{{ $message }}</p>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Items</h2>

            @if(auth()->user()->can('create_{KEBAB}_item'))
                <form method="POST" action="{{ route('{KEBAB}.store') }}" class="mb-6">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="name" placeholder="Item name"
                               class="flex-1 px-3 py-2 border rounded" required>
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Add Item
                        </button>
                    </div>
                </form>
            @endif

            <p class="text-gray-500">No items yet.</p>
        </div>
    </div>
</div>
BLADE;

        $indexView = str_replace('{KEBAB}', $kebab, $indexView);
        $this->files->put("{$bundlePath}/src/Views/index.blade.php", $indexView);

        // Edit view
        $editView = <<<'BLADE'
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Edit Item</h1>

        <form method="POST" action="{{ route('{KEBAB}.update', $id) }}" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded"></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
                <a href="{{ route('{KEBAB}.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
BLADE;

        $editView = str_replace('{KEBAB}', $kebab, $editView);
        $this->files->put("{$bundlePath}/src/Views/edit.blade.php", $editView);
    }

    private function createMigration(string $bundlePath, string $bundleName): void
    {
        $tableName = Str::snake(Str::plural($bundleName));
        $timestamp = now()->format('YmdHis');

        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('{TABLE_NAME}', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{TABLE_NAME}');
    }
};
PHP;

        $content = str_replace('{TABLE_NAME}', $tableName, $content);

        $this->files->put(
            "{$bundlePath}/src/Database/migrations/{$timestamp}_create_{$tableName}_table.php",
            $content
        );
    }

    private function createReadme(string $bundlePath, string $bundleName, string $packagePath, string $description): void
    {
        $kebab = Str::kebab($bundleName);

        $readme = <<<'MARKDOWN'
# {BUNDLE_NAME}

{DESCRIPTION}

## Installation

Upload the bundle ZIP via Bundle Installer at `/bundle-installer`

## Features

- ✅ Role-based permissions (Admin, Teacher)
- ✅ Menu item injection (no app file modifications)
- ✅ CRUD routes with permission checks
- ✅ Sample views and controller
- ✅ Database migration included

## Permissions

- `view_{KEBAB}` - View {BUNDLE_NAME} items
- `create_{KEBAB}_item` - Create new item
- `edit_{KEBAB}_item` - Edit items
- `delete_{KEBAB}_item` - Delete items

## Routes

- `GET /{{KEBAB}}/` - List items
- `POST /{{KEBAB}}/` - Create item
- `GET /{{KEBAB}}/{{id}}/edit` - Edit form
- `PUT /{{KEBAB}}/{{id}}` - Update item
- `DELETE /{{KEBAB}}/{{id}}` - Delete item

## Usage

1. After installation, permissions are auto-created
2. Assign roles to users
3. Access at `/{KEBAB}`
4. Menu item appears automatically based on permissions

## Customize

- Edit `src/Controllers/{BUNDLE_NAME}Controller.php` - Add your logic
- Edit `src/Views/` - Customize views
- Edit `manifest.json` - Update bundle details
- Edit `src/Database/migrations/` - Modify schema

## Package Structure

```
{PACKAGE_PATH}/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── {BUNDLE_NAME}ServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── {BUNDLE_NAME}Controller.php
    ├── Models/
    ├── Routes/
    │   └── web.php
    ├── Views/
    │   ├── index.blade.php
    │   └── edit.blade.php
    ├── Database/
    │   └── migrations/
    └── Assets/
```

## Testing

```bash
# Check if routes exist
php artisan route:list | grep {KEBAB}

# Check if permissions exist
php artisan tinker
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%{KEBAB}%')->get()

# Test as different role
>>> $user = User::first();
>>> $user->assignRole('teacher');
>>> $user->hasPermissionTo('view_{KEBAB}')
=> true
```

## Support

See main bundle documentation at `/app/Packages/BundleInstaller/`
MARKDOWN;

        $readme = str_replace([
            '{BUNDLE_NAME}',
            '{DESCRIPTION}',
            '{KEBAB}',
            '{PACKAGE_PATH}',
        ], [
            $bundleName,
            $description,
            $kebab,
            $packagePath,
        ], $readme);

        $this->files->put("{$bundlePath}/README.md", $readme);
    }

    private function showNextSteps(string $packagePath, string $bundleName): void
    {
        $this->line('📋 Next Steps:');
        $this->line('');
        $this->line('1. <fg=cyan>Edit your files</>');
        $this->line("   - Controllers: <fg=yellow>app/Packages/{$packagePath}/src/Controllers/</>");
        $this->line("   - Views: <fg=yellow>app/Packages/{$packagePath}/src/Views/</>");
        $this->line("   - Models: <fg=yellow>app/Packages/{$packagePath}/src/Models/</>");
        $this->line('');
        $this->line('2. <fg=cyan>Create ZIP bundle</>');
        $this->line("   cd app/Packages/{$packagePath}");
        $this->line('   zip -r ../' . $bundleName . '.zip .');
        $this->line('');
        $this->line('3. <fg=cyan>Upload via UI</>');
        $this->line('   Go to http://localhost:8000/bundle-installer');
        $this->line('   Upload the ZIP file');
        $this->line('');
        $this->line('4. <fg=cyan>System auto-registers</>');
        $this->line('   - PSR-4 namespace in composer.json');
        $this->line('   - Service provider in bootstrap/providers.php');
        $this->line('   - Permissions in database');
        $this->line('   - Menu items in navigation');
        $this->line('');
    }
}

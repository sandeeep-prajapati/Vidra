# Complete Bundle Development Guide

Full example showing permissions + menu injection + routing in one complete bundle.

## Complete Example: Student Management Premium Bundle

### 1. Directory Structure

```
Pro/StudentManagement/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── StudentManagementServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── StudentController.php
    ├── Http/
    │   └── Middleware/
    │       └── CheckStudentAccess.php
    ├── Models/
    │   └── Student.php
    ├── Routes/
    │   └── web.php
    ├── Views/
    │   ├── index.blade.php
    │   ├── show.blade.php
    │   ├── edit.blade.php
    │   └── analytics.blade.php
    └── Database/
        └── migrations/
            └── 2026_01_01_000000_create_students_table.php
```

### 2. Service Provider with Permissions

File: `src/Providers/StudentManagementServiceProvider.php`

```php
<?php

namespace App\Packages\Pro\StudentManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Register permissions
        $this->registerPermissions();

        // 2. Load everything
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'student-management');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_students',
            'create_student',
            'edit_student',
            'delete_student',
            'view_student_analytics',
            'export_students',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Assign to roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // Create roles if they don't exist
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);

        // Admin: All permissions
        $admin->syncPermissions([
            'view_students',
            'create_student',
            'edit_student',
            'delete_student',
            'view_student_analytics',
            'export_students',
        ]);

        // Teacher: View and Edit only
        $teacher->syncPermissions([
            'view_students',
            'edit_student',
            'view_student_analytics',
        ]);

        // Student: View only
        $student->syncPermissions([
            'view_students',
        ]);
    }
}
```

### 3. Menu Provider

File: `src/Providers/MenuProvider.php`

```php
<?php

namespace App\Packages\Pro\StudentManagement\Providers;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $items = [];

        // Main Students menu
        if (auth()->user()->can('view_students')) {
            $items[] = [
                'label' => 'Students',
                'route' => 'students.index',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5M10.5 1.5v4m0-4h9m-9 4h9M1.5 10.5h9m-6 4.5h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
                'active' => request()->routeIs('students.*'),
                'badge' => auth()->user()->can('create_student') ? null : null,
            ];
        }

        // Analytics (Teachers & Admin only)
        if (auth()->user()->can('view_student_analytics')) {
            $items[] = [
                'label' => 'Student Analytics',
                'route' => 'students.analytics',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>',
                'active' => request()->routeIs('students.analytics'),
            ];
        }

        // Admin-only actions
        if (auth()->user()->hasRole('admin')) {
            $items[] = [
                'type' => 'divider',
            ];
            $items[] = [
                'label' => 'Student Admin',
                'submenu' => [
                    [
                        'label' => 'Export Students',
                        'route' => 'students.export',
                        'active' => request()->routeIs('students.export'),
                    ],
                    [
                        'label' => 'Bulk Import',
                        'route' => 'students.import',
                        'active' => request()->routeIs('students.import'),
                    ],
                ],
            ];
        }

        return $items;
    }
}
```

### 4. Routes with Permissions

File: `src/Routes/web.php`

```php
<?php

use App\Packages\Pro\StudentManagement\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('students')->group(function () {
        
        // List students
        Route::get('/', [StudentController::class, 'index'])
            ->middleware('permission:view_students')
            ->name('students.index');

        // Create student form (for authorized users)
        Route::get('/create', [StudentController::class, 'create'])
            ->middleware('permission:create_student')
            ->name('students.create');

        // Store new student
        Route::post('/', [StudentController::class, 'store'])
            ->middleware('permission:create_student')
            ->name('students.store');

        // Show student
        Route::get('/{student}', [StudentController::class, 'show'])
            ->middleware('permission:view_students')
            ->name('students.show');

        // Edit student form
        Route::get('/{student}/edit', [StudentController::class, 'edit'])
            ->middleware('permission:edit_student')
            ->name('students.edit');

        // Update student
        Route::put('/{student}', [StudentController::class, 'update'])
            ->middleware('permission:edit_student')
            ->name('students.update');

        // Delete student
        Route::delete('/{student}', [StudentController::class, 'destroy'])
            ->middleware('permission:delete_student')
            ->name('students.destroy');

        // Analytics
        Route::get('/analytics/dashboard', [StudentController::class, 'analytics'])
            ->middleware('permission:view_student_analytics')
            ->name('students.analytics');

        // Export
        Route::post('/export', [StudentController::class, 'export'])
            ->middleware('permission:export_students')
            ->name('students.export');
    });
});
```

### 5. Controller with Authorization Checks

File: `src/Controllers/StudentController.php`

```php
<?php

namespace App\Packages\Pro\StudentManagement\Controllers;

use App\Packages\Pro\StudentManagement\Models\Student;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends BaseController
{
    public function index(): View
    {
        // Permission checked by middleware, but check again for safety
        if (!auth()->user()->can('view_students')) {
            abort(403, 'Unauthorized');
        }

        $students = Student::paginate(15);
        return view('student-management::index', [
            'students' => $students,
            'canCreate' => auth()->user()->can('create_student'),
            'canEdit' => auth()->user()->can('edit_student'),
            'canDelete' => auth()->user()->can('delete_student'),
        ]);
    }

    public function show(Student $student): View
    {
        if (!auth()->user()->can('view_students')) {
            abort(403);
        }

        return view('student-management::show', ['student' => $student]);
    }

    public function create(): View
    {
        $this->authorize('create', Student::class);
        return view('student-management::create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'roll_number' => 'required|unique:students',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully');
    }

    public function edit(Student $student): View
    {
        $this->authorize('update', $student);
        return view('student-management::edit', ['student' => $student]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->authorize('delete', $student);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function analytics(): View
    {
        if (!auth()->user()->can('view_student_analytics')) {
            abort(403);
        }

        $totalStudents = Student::count();
        $newThisMonth = Student::whereMonth('created_at', now()->month)->count();

        return view('student-management::analytics', [
            'totalStudents' => $totalStudents,
            'newThisMonth' => $newThisMonth,
        ]);
    }

    public function export(Request $request)
    {
        if (!auth()->user()->can('export_students')) {
            abort(403);
        }

        $students = Student::all();

        // Create CSV
        $filename = 'students_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->streamDownload(function () use ($students) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Name', 'Email', 'Roll Number']);

            foreach ($students as $student) {
                fputcsv($output, [
                    $student->name,
                    $student->email,
                    $student->roll_number,
                ]);
            }

            fclose($output);
        }, $filename, $headers);
    }
}
```

### 6. Model with Authorization Policies

File: `src/Models/Student.php`

```php
<?php

namespace App\Packages\Pro\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'roll_number',
        'class',
        'section',
    ];

    // Boot model events
    protected static function booted()
    {
        static::creating(function ($student) {
            // Only admins and teachers can create
            if (!auth()->user()?->can('create_student')) {
                throw new \Exception('Unauthorized');
            }
        });
    }
}
```

### 7. Manifest File

File: `manifest.json`

```json
{
    "name": "Student Management Premium",
    "version": "2.0.0",
    "description": "Premium bundle for comprehensive student management with analytics and bulk operations",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/StudentManagement",
    "provider_class": "App\\Packages\\Pro\\StudentManagement\\Providers\\StudentManagementServiceProvider"
}
```

## Usage Flow

### 1. Upload Bundle
```bash
cd app/Packages/Pro/StudentManagement
zip -r ../StudentManagement.zip .
# Upload via /bundle-installer UI
```

### 2. Permissions Auto-Created
Service provider automatically creates:
- `view_students`
- `create_student`
- `edit_student`
- `delete_student`
- `view_student_analytics`
- `export_students`

And assigns to roles:
- Admin: All permissions
- Teacher: View, Edit, Analytics
- Student: View only

### 3. Menu Items Auto-Injected
MenuProvider automatically adds:
- Students menu item (if user can view)
- Analytics menu item (if user can view analytics)
- Admin submenu with Export & Import (admin only)

### 4. Routes Protected
All routes are protected by:
- `auth` middleware (logged in)
- `permission` middleware (has permission)
- Controller authorization checks

## Testing Permissions

```bash
# SSH into app
php artisan tinker

# Create test user with role
$user = User::first();
$user->assignRole('teacher');

# Check permissions
$user->hasPermissionTo('view_students')  # true
$user->hasPermissionTo('create_student')  # false (teacher only has view/edit)
$user->hasPermissionTo('delete_student')  # false

# Give specific permission
$user->givePermissionTo('create_student');
$user->hasPermissionTo('create_student')  # true

# Remove from role
$user->removeRole('teacher');
$user->hasRole('teacher')  # false
```

## Testing Menu Items

```blade
<!-- In any blade template -->
@php
    use App\Helpers\BundleMenuHelper;
    $menuItems = BundleMenuHelper::discoverMenuItems();
    dd($menuItems);
@endphp
```

Should show:
- Students (all authenticated users who can view)
- Analytics (teachers and admins)
- Admin submenu (admins only)

## Best Practices Summary

✅ Register permissions in service provider  
✅ Protect routes with middleware  
✅ Check permissions in controllers too  
✅ Use MenuProvider for menu items  
✅ Keep all code within bundle  
✅ Document permissions in README  
✅ Test with different roles  
✅ Use meaningful permission names  

❌ Don't modify app files  
❌ Don't hardcode user checks  
❌ Don't forget permission checks  
❌ Don't use unclear naming  
❌ Don't skip controller checks  

## Documentation Files

- `PERMISSIONS_GUIDE.md` - Detailed permission system
- `MENU_INJECTION_GUIDE.md` - Menu system details
- `README.md` - Bundle system overview
- `BUNDLE_NAMING_CONVENTION.md` - Naming conventions
- `ZIP_STRUCTURE_GUIDE.md` - ZIP file structure
- This file - Complete working example

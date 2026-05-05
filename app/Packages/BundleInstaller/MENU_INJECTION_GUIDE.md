# Bundle Menu Injection System

Allow bundles to register their own menu items automatically without modifying app files.

## Overview

Bundles can inject menu items into the main navigation without editing the main application layout. This is done through a MenuProvider within each bundle that registers menu items.

## Implementation

### 1. Create Menu Provider in Bundle

Create `src/Providers/MenuProvider.php` in your bundle:

```php
<?php

namespace App\Packages\Pro\MyBundle\Providers;

use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    /**
     * Register menu items for this bundle
     * These are automatically discovered and injected
     */
    public static function getMenuItems(): array
    {
        return [
            [
                'label' => 'My Bundle',
                'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3',
                'route' => 'mybundle.index',
                'active' => request()->routeIs('mybundle.*'),
                'badge' => 5,  // Optional: Show count
                'permission' => 'view_my_bundle',  // Optional: Permission check
            ],
        ];
    }
}
```

### 2. Register in Service Provider

In your main bundle service provider:

```php
<?php

namespace App\Packages\Pro\MyBundle\Providers;

use Illuminate\Support\ServiceProvider;

class MyBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register menu items
        $this->registerMenuItems();
        
        // Other boot logic
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'my-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    private function registerMenuItems(): void
    {
        // This makes menu items available globally
        if (class_exists(\App\Packages\Pro\MyBundle\Providers\MenuProvider::class)) {
            // Menu items will be auto-discovered
        }
    }
}
```

### 3. Add Menu Items to Layout

In your main app layout (e.g., `resources/views/layouts/app.blade.php`):

```blade
<!-- Sidebar Navigation -->
<aside class="sidebar">
    <nav class="menu">
        @php
            $menuItems = [];
            
            // Discover menu items from all bundles
            $bundlePath = base_path('app/Packages');
            if (is_dir($bundlePath)) {
                $this->discoverBundleMenuItems($bundlePath, $menuItems);
            }
            
            // Sort by label
            usort($menuItems, fn($a, $b) => $a['label'] <=> $b['label']);
        @endphp

        @foreach($menuItems as $item)
            @if(!isset($item['permission']) || auth()->user()?->can($item['permission']))
                <a href="{{ route($item['route']) }}"
                   class="menu-item {{ $item['active'] ?? false ? 'active' : '' }}">
                    @if(isset($item['icon']))
                        <svg class="icon">
                            {!! $item['icon'] !!}
                        </svg>
                    @endif
                    <span>{{ $item['label'] }}</span>
                    @if(isset($item['badge']))
                        <span class="badge">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>
</aside>
```

### 4. Create Helper Function

Create `app/Helpers/BundleMenuHelper.php`:

```php
<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class BundleMenuHelper
{
    public static function discoverMenuItems(): array
    {
        $menuItems = [];
        $bundlesPath = base_path('app/Packages');

        if (!is_dir($bundlesPath)) {
            return [];
        }

        // Recursively find MenuProvider classes
        $files = File::allFiles($bundlesPath);

        foreach ($files as $file) {
            if ($file->getFilename() === 'MenuProvider.php') {
                try {
                    // Extract namespace and class
                    $content = File::get($file->getPathname());
                    
                    if (preg_match('/namespace\s+([\w\\\\]+)/', $content, $matches)) {
                        $namespace = $matches[1];
                        $className = "{$namespace}\\MenuProvider";
                        
                        if (class_exists($className) && method_exists($className, 'getMenuItems')) {
                            $items = $className::getMenuItems();
                            $menuItems = array_merge($menuItems, $items);
                        }
                    }
                } catch (\Exception $e) {
                    // Skip on error
                    continue;
                }
            }
        }

        return $menuItems;
    }
}
```

Use in layout:

```blade
@php
    use App\Helpers\BundleMenuHelper;
    $menuItems = BundleMenuHelper::discoverMenuItems();
@endphp

@foreach($menuItems as $item)
    <!-- Render menu item -->
@endforeach
```

## Advanced Features

### 1. Menu Item with Submenu

```php
public static function getMenuItems(): array
{
    return [
        [
            'label' => 'Management',
            'icon' => '...',
            'submenu' => [
                [
                    'label' => 'Students',
                    'route' => 'students.index',
                    'active' => request()->routeIs('students.*'),
                ],
                [
                    'label' => 'Teachers',
                    'route' => 'teachers.index',
                    'active' => request()->routeIs('teachers.*'),
                ],
            ],
        ],
    ];
}
```

Render in layout:

```blade
@foreach($menuItems as $item)
    @if(isset($item['submenu']))
        <details class="menu-group">
            <summary>{{ $item['label'] }}</summary>
            <nav class="submenu">
                @foreach($item['submenu'] as $subitem)
                    <a href="{{ route($subitem['route']) }}"
                       class="menu-item {{ $subitem['active'] ?? false ? 'active' : '' }}">
                        {{ $subitem['label'] }}
                    </a>
                @endforeach
            </nav>
        </details>
    @endif
@endforeach
```

### 2. Conditional Menu Items

```php
public static function getMenuItems(): array
{
    $items = [];

    // Only show if user is admin
    if (auth()->user()?->hasRole('admin')) {
        $items[] = [
            'label' => 'Admin Dashboard',
            'route' => 'admin.dashboard',
            'icon' => '...',
        ];
    }

    // Only show if feature is enabled
    if (config('features.reports_enabled')) {
        $items[] = [
            'label' => 'Reports',
            'route' => 'reports.index',
            'icon' => '...',
        ];
    }

    return $items;
}
```

### 3. Menu Item with Badge

```php
public static function getMenuItems(): array
{
    return [
        [
            'label' => 'Messages',
            'route' => 'messages.index',
            'icon' => '...',
            'badge' => auth()->user()?->unreadMessages()->count() ?? 0,
            'badge_color' => 'red',  // Optional
        ],
    ];
}
```

### 4. Menu Item with Divider

```php
public static function getMenuItems(): array
{
    return [
        [
            'label' => 'Core Features',
            'type' => 'section',  // Section header
        ],
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '...',
        ],
        [
            'type' => 'divider',  // Visual separator
        ],
        [
            'label' => 'Premium Features',
            'type' => 'section',
        ],
        [
            'label' => 'Advanced Reports',
            'route' => 'reports.advanced',
            'icon' => '...',
        ],
    ];
}
```

## Complete Example: Student Management Bundle

### Bundle Service Provider

```php
<?php

namespace App\Packages\Pro\StudentManagement\Providers;

use Illuminate\Support\ServiceProvider;

class StudentManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'student-management');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
```

### Menu Provider

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

        // View students (all roles)
        if (auth()->user()->can('view_students')) {
            $items[] = [
                'label' => 'Students',
                'route' => 'students.index',
                'icon' => '<svg>...</svg>',
                'active' => request()->routeIs('students.*'),
            ];
        }

        // Student stats (teachers & admin)
        if (auth()->user()->can('view_student_stats')) {
            $items[] = [
                'label' => 'Student Analytics',
                'route' => 'students.analytics',
                'icon' => '<svg>...</svg>',
                'active' => request()->routeIs('students.analytics'),
                'badge' => 3,  // New students this week
            ];
        }

        // Bulk operations (admin only)
        if (auth()->user()->hasRole('admin')) {
            $items[] = [
                'type' => 'divider',
            ];
            $items[] = [
                'label' => 'Bulk Operations',
                'submenu' => [
                    [
                        'label' => 'Import Students',
                        'route' => 'students.import',
                    ],
                    [
                        'label' => 'Export Data',
                        'route' => 'students.export',
                    ],
                ],
            ];
        }

        return $items;
    }
}
```

### App Layout Integration

```blade
<!-- app.blade.php -->
@extends('core-package::layouts.app')

@section('sidebar')
    @php
        $allMenuItems = [
            // Core app items
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => '<svg>...</svg>',
                'active' => request()->routeIs('dashboard'),
            ],
        ];

        // Add bundle menu items
        $allMenuItems = array_merge(
            $allMenuItems,
            \App\Helpers\BundleMenuHelper::discoverMenuItems()
        );
    @endphp

    <nav class="sidebar-menu">
        @foreach($allMenuItems as $item)
            @if($item['type'] ?? null === 'divider')
                <hr class="my-2">
            @elseif($item['type'] ?? null === 'section')
                <div class="menu-section-header">{{ $item['label'] }}</div>
            @elseif(isset($item['submenu']))
                <details class="menu-group {{ $item['active'] ?? false ? 'open' : '' }}">
                    <summary>{{ $item['label'] }}</summary>
                    <nav>
                        @foreach($item['submenu'] as $sub)
                            <a href="{{ route($sub['route']) }}"
                               class="{{ $sub['active'] ?? false ? 'active' : '' }}">
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </details>
            @else
                @php
                    $canAccess = !isset($item['permission']) || 
                                 auth()->user()?->can($item['permission']);
                @endphp
                @if($canAccess)
                    <a href="{{ route($item['route']) }}"
                       class="menu-item {{ $item['active'] ?? false ? 'active' : '' }}">
                        @if(isset($item['icon']))
                            <span class="icon">{!! $item['icon'] !!}</span>
                        @endif
                        <span class="label">{{ $item['label'] }}</span>
                        @if(isset($item['badge']))
                            <span class="badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endif
            @endif
        @endforeach
    </nav>
@endsection
```

## Menu Item Structure

```php
[
    'label' => 'Menu Item Label',           // Required
    'route' => 'route.name',                // Required (unless submenu or section)
    'icon' => '<svg>...</svg>',             // Optional: SVG icon
    'active' => true/false,                 // Optional: Is currently active
    'badge' => 5,                           // Optional: Badge count
    'badge_color' => 'red',                 // Optional: Badge color
    'permission' => 'view_something',       // Optional: Permission check
    'permission_all' => true/false,         // Optional: Check all perms
    'visible_if' => condition,              // Optional: Custom condition
    'submenu' => [...],                     // Optional: Nested menu items
    'type' => 'section|divider|normal',     // Optional: Item type
    'order' => 10,                          // Optional: Sort order
]
```

## Best Practices

✅ **DO:**
- Return empty array if user can't access
- Use permission checks
- Keep menu items relevant to bundle
- Use meaningful labels
- Include helpful badges/counts
- Document menu structure in README

❌ **DON'T:**
- Add items directly to app layout
- Hardcode user checks
- Add too many menu items
- Use unclear labels
- Forget to check permissions
- Modify app files from bundle

## Troubleshooting

**Menu items not showing?**
1. Check MenuProvider class exists
2. Check `getMenuItems()` method is public and static
3. Verify helper is discovering the provider
4. Check permissions if menu is conditionally shown

**Permission check not working?**
1. Verify permission exists in database
2. Check user has role with permission
3. Clear cache: `php artisan cache:clear`

**Menu items in wrong order?**
1. Add `'order'` field to menu items
2. Sort menu items in helper: `usort($items, fn($a,$b) => ($a['order']??99) <=> ($b['order']??99))`

## Related Documentation

- PERMISSIONS_GUIDE.md - Role-based access control
- Bundle Routing Guide
- Bundle Structure Guide

# TestBundle

Test bundle

## Installation

Upload the bundle ZIP via Bundle Installer at `/bundle-installer`

## Features

- ✅ Role-based permissions (Admin, Teacher)
- ✅ Menu item injection (no app file modifications)
- ✅ CRUD routes with permission checks
- ✅ Sample views and controller
- ✅ Database migration included

## Permissions

- `view_test-bundle` - View TestBundle items
- `create_test-bundle_item` - Create new item
- `edit_test-bundle_item` - Edit items
- `delete_test-bundle_item` - Delete items

## Routes

- `GET /{test-bundle}/` - List items
- `POST /{test-bundle}/` - Create item
- `GET /{test-bundle}/{{id}}/edit` - Edit form
- `PUT /{test-bundle}/{{id}}` - Update item
- `DELETE /{test-bundle}/{{id}}` - Delete item

## Usage

1. After installation, permissions are auto-created
2. Assign roles to users
3. Access at `/test-bundle`
4. Menu item appears automatically based on permissions

## Customize

- Edit `src/Controllers/TestBundleController.php` - Add your logic
- Edit `src/Views/` - Customize views
- Edit `manifest.json` - Update bundle details
- Edit `src/Database/migrations/` - Modify schema

## Package Structure

```
Pro/TestBundle/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── TestBundleServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── TestBundleController.php
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
php artisan route:list | grep test-bundle

# Check if permissions exist
php artisan tinker
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%test-bundle%')->get()

# Test as different role
>>> $user = User::first();
>>> $user->assignRole('teacher');
>>> $user->hasPermissionTo('view_test-bundle')
=> true
```

## Support

See main bundle documentation at `/app/Packages/BundleInstaller/`
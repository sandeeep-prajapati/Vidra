# AIBasedReporting

A premium bundle

## Installation

Upload the bundle ZIP via Bundle Installer at `/bundle-installer`

## Features

- ✅ Role-based permissions (Admin, Teacher)
- ✅ Menu item injection (no app file modifications)
- ✅ CRUD routes with permission checks
- ✅ Sample views and controller
- ✅ Database migration included

## Permissions

- `view_a-i-based-reporting` - View AIBasedReporting items
- `create_a-i-based-reporting_item` - Create new item
- `edit_a-i-based-reporting_item` - Edit items
- `delete_a-i-based-reporting_item` - Delete items

## Routes

- `GET /{a-i-based-reporting}/` - List items
- `POST /{a-i-based-reporting}/` - Create item
- `GET /{a-i-based-reporting}/{{id}}/edit` - Edit form
- `PUT /{a-i-based-reporting}/{{id}}` - Update item
- `DELETE /{a-i-based-reporting}/{{id}}` - Delete item

## Usage

1. After installation, permissions are auto-created
2. Assign roles to users
3. Access at `/a-i-based-reporting`
4. Menu item appears automatically based on permissions

## Customize

- Edit `src/Controllers/AIBasedReportingController.php` - Add your logic
- Edit `src/Views/` - Customize views
- Edit `manifest.json` - Update bundle details
- Edit `src/Database/migrations/` - Modify schema

## Package Structure

```
Pro/AIBasedReporting/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── AIBasedReportingServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── AIBasedReportingController.php
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
php artisan route:list | grep a-i-based-reporting

# Check if permissions exist
php artisan tinker
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%a-i-based-reporting%')->get()

# Test as different role
>>> $user = User::first();
>>> $user->assignRole('teacher');
>>> $user->hasPermissionTo('view_a-i-based-reporting')
=> true
```

## Support

See main bundle documentation at `/app/Packages/BundleInstaller/`
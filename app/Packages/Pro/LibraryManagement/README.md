# LibraryManagement

Complete school library management system with book catalog, member management, issue tracking, and fine management

## Installation

Upload the bundle ZIP via Bundle Installer at `/bundle-installer`

## Features

- ✅ Role-based permissions (Admin, Teacher)
- ✅ Menu item injection (no app file modifications)
- ✅ CRUD routes with permission checks
- ✅ Sample views and controller
- ✅ Database migration included

## Permissions

- `view_library-management` - View LibraryManagement items
- `create_library-management_item` - Create new item
- `edit_library-management_item` - Edit items
- `delete_library-management_item` - Delete items

## Routes

- `GET /{library-management}/` - List items
- `POST /{library-management}/` - Create item
- `GET /{library-management}/{{id}}/edit` - Edit form
- `PUT /{library-management}/{{id}}` - Update item
- `DELETE /{library-management}/{{id}}` - Delete item

## Usage

1. After installation, permissions are auto-created
2. Assign roles to users
3. Access at `/library-management`
4. Menu item appears automatically based on permissions

## Customize

- Edit `src/Controllers/LibraryManagementController.php` - Add your logic
- Edit `src/Views/` - Customize views
- Edit `manifest.json` - Update bundle details
- Edit `src/Database/migrations/` - Modify schema

## Package Structure

```
Pro/LibraryManagement/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── LibraryManagementServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── LibraryManagementController.php
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
php artisan route:list | grep library-management

# Check if permissions exist
php artisan tinker
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%library-management%')->get()

# Test as different role
>>> $user = User::first();
>>> $user->assignRole('teacher');
>>> $user->hasPermissionTo('view_library-management')
=> true
```

## Support

See main bundle documentation at `/app/Packages/BundleInstaller/`
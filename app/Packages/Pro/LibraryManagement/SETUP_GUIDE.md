# LibraryManagement Bundle - Setup System Guide

Complete guide to using the setup wizard and custom command execution system.

---

## 🚀 Quick Setup

### Step 1: Upload Bundle
1. Go to `http://localhost:8000/bundle-installer`
2. Upload `LibraryManagement.zip`
3. System auto-registers everything

### Step 2: Run Setup Wizard
Visit: **`http://localhost:8000/library/setup`**

You'll see 4 status cards:
- ✓ Migrations (green if complete)
- ✓ Permissions (green if complete)
- ✓ Roles (green if complete)
- ✓ Categories (green if complete)

### Step 3: Initialize System
Click the orange **"Run All Setup"** button

This automatically:
1. ✅ Creates 6 database tables
2. ✅ Creates 5 permissions
3. ✅ Sets up 3 roles (admin, librarian, teacher)
4. ✅ Seeds 6 book categories

### Step 4: Start Using
After setup completes, access:
- **Books:** `http://localhost:8000/library/books`
- **Members:** `http://localhost:8000/library/members`
- **Issues:** `http://localhost:8000/library/issues`
- **Fines:** `http://localhost:8000/library/fines`

---

## 🔧 Setup Endpoint Details

### Route
```
GET  /library/setup      → Shows setup UI
POST /library/setup/run  → Executes commands
```

### Setup UI (`/library/setup`)
Interactive dashboard showing:
- Current setup status (4 cards)
- Status indicators (✓ Done / ⚠ Pending)
- Setup buttons (5 options)
- Progress tracking
- Success/error messages

### Individual Commands

#### 1. **Migrations**
Button: "Run Migrations"  
Command: `migrate`
- Creates 6 tables: categories, books, members, issues, fines, reservations
- Sets up foreign keys and indexes
- Time: ~1 second

#### 2. **Permissions**
Button: "Setup Permissions"  
Command: `permissions`
- Creates 5 permissions:
  - `view_library-management`
  - `create_library-management_item`
  - `edit_library-management_item`
  - `delete_library-management_item`
  - `manage_library_fines`
- Time: ~1 second

#### 3. **Roles**
Button: "Setup Roles"  
Command: `roles`
- Creates/updates 3 roles:
  - **Admin:** All 5 permissions
  - **Librarian:** All 5 permissions
  - **Teacher:** View only
- Assigns permissions to each role
- Time: ~1 second

#### 4. **Seed Data**
Button: "Seed Initial Data"  
Command: `seed-data`
- Creates 6 book categories:
  - Fiction
  - Science
  - Mathematics
  - History
  - Reference
  - Children
- Time: ~1 second

#### 5. **Run All Setup**
Button: "⚡ Run All Setup"  
Command: `all`
- Executes all 4 commands in sequence
- Total time: ~5 seconds
- Recommended for first-time setup

---

## 📡 API Usage

### GET /library/setup
Returns setup UI page.

```bash
curl http://localhost:8000/library/setup
```

### POST /library/setup/run
Executes a command and returns JSON response.

```bash
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {csrf-token}" \
  -d '{"command":"migrate"}'
```

### Request Format
```json
{
  "command": "migrate"  // or: permissions, roles, seed-data, all
}
```

### Response Format (Success)
```json
{
  "success": true,
  "message": "Migrations completed successfully",
  "step": "migrate"
}
```

### Response Format (Multiple Steps - `all` command)
```json
{
  "success": true,
  "message": "All setup steps completed",
  "steps": [
    {
      "success": true,
      "message": "Migrations completed successfully",
      "step": "migrate"
    },
    {
      "success": true,
      "message": "5 permissions created/updated",
      "step": "permissions"
    },
    ...
  ]
}
```

### Response Format (Error)
```json
{
  "success": false,
  "message": "Error: Table 'library_books' already exists"
}
```

---

## 🛠️ Extending Setup System

### Add Custom Command

Edit `src/Controllers/SetupController.php`:

```php
// 1. Add method
private function customSetup(): array
{
    try {
        // Your custom logic here
        Artisan::call('your:command');
        
        // Or create records
        Category::create(['name' => 'Custom']);
        
        return [
            'success' => true,
            'message' => 'Custom setup completed',
            'step' => 'custom-setup',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ];
    }
}

// 2. Add case in runSetup()
case 'custom' => $this->customSetup(),

// 3. Add button in view
<button onclick="runSetup('custom')" class="w-full px-4 py-3 bg-cyan-600...">
    Custom Setup Step
</button>
```

### Example: Seed Sample Books

```php
private function seedSampleBooks(): array
{
    try {
        $category = \App\Packages\Pro\LibraryManagement\Models\LibraryCategory::first();
        
        $books = [
            ['title' => 'Harry Potter', 'author' => 'J.K. Rowling', 'total_copies' => 3],
            ['title' => '1984', 'author' => 'George Orwell', 'total_copies' => 2],
        ];
        
        foreach ($books as $book) {
            \App\Packages\Pro\LibraryManagement\Models\LibraryBook::create([
                'category_id' => $category->id,
                'available_copies' => $book['total_copies'],
                ...$book
            ]);
        }
        
        return [
            'success' => true,
            'message' => count($books) . ' books seeded',
            'step' => 'seed-books',
        ];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
```

---

## 📊 Setup Status Checks

The UI checks 4 status indicators:

### 1. Migrations Check
```php
private function checkMigrationsRun(): bool
{
    return DB::table('information_schema.tables')
        ->where('table_schema', env('DB_DATABASE'))
        ->where('table_name', 'library_books')
        ->exists();
}
```

### 2. Permissions Check
```php
private function checkPermissionsExist(): bool
{
    return Permission::where('name', 'view_library-management')->exists();
}
```

### 3. Roles Check
```php
private function checkRolesExist(): bool
{
    return Role::where('name', 'librarian')->exists();
}
```

### 4. Categories Check
```php
private function checkCategoriesExist(): bool
{
    return LibraryCategory::count() > 0;
}
```

---

## 🔄 Setup Flow Diagram

```
User visits /library/setup
        ↓
[Setup Page Loads]
  ├─ Checks: Migrations? ✓/⚠
  ├─ Checks: Permissions? ✓/⚠
  ├─ Checks: Roles? ✓/⚠
  └─ Checks: Data? ✓/⚠
        ↓
User clicks button (e.g., "Run All Setup")
        ↓
POST /library/setup/run
  └─ command: "all"
        ↓
SetupController@runSetup()
  ├─ runMigrations()     [1 sec]
  ├─ setupPermissions()  [1 sec]
  ├─ setupRoles()        [1 sec]
  └─ seedInitialData()   [1 sec]
        ↓
Returns JSON success
        ↓
UI updates with checkmarks ✓
        ↓
Auto-reload after 2 seconds
        ↓
User can now access /library/books
```

---

## 🧪 Testing Setup

### Test Individual Commands
```bash
# Test migrations
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -d '{"command":"migrate"}'

# Test permissions
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -d '{"command":"permissions"}'

# Test roles
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -d '{"command":"roles"}'

# Test data seeding
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -d '{"command":"seed-data"}'

# Test all commands
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -d '{"command":"all"}'
```

### Verify Setup Completion

```bash
# Check tables exist
php artisan tinker
>>> use Illuminate\Support\Facades\DB;
>>> DB::table('library_books')->count()
=> 0

>>> DB::table('library_categories')->count()
=> 6

# Check permissions
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%library%')->count()
=> 5

# Check roles
>>> use Spatie\Permission\Models\Role;
>>> Role::where('name', 'librarian')->exists()
=> true
```

---

## ⚠️ Troubleshooting

### "Setup route not found"
```bash
# Solution: Clear cache and reload
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

### "Migrations failed: Table already exists"
```bash
# Solution: Migrations were already run, skip this step
# Or rollback and rerun:
php artisan migrate:rollback
php artisan migrate
```

### "Permissions/Roles not working"
```bash
# Solution: Ensure Spatie permission package is installed
composer require spatie/laravel-permission

# Then run setup again
```

### "CSRF token validation failed"
```bash
# Solution: Include X-CSRF-TOKEN header in POST requests
-H "X-CSRF-TOKEN: {{ csrf_token() }}"
```

### "Setup page shows all ⚠ symbols"
- Migrations not run yet → Click "Run All Setup"
- Database credentials wrong → Check .env file
- Spatie package missing → Run `composer require spatie/laravel-permission`

---

## 📋 Setup Checklist

After running "Run All Setup", verify:

- [ ] Visit `/library/setup` shows all ✓ green
- [ ] Can access `/library/books`
- [ ] Can access `/library/members`
- [ ] Can access `/library/issues`
- [ ] Can access `/library/fines`
- [ ] User has `view_library-management` permission
- [ ] `librarian` role exists in database
- [ ] 6 book categories exist (Fiction, Science, etc.)

---

## 🎯 Next Steps After Setup

1. **Create Test Data**
   - Add 5-10 test books via `/library/books`
   - Register members via `/library/members`

2. **Assign Roles**
   - Assign `librarian` role to library staff
   - Assign `teacher` role to teachers

3. **Test Workflows**
   - Issue a book to a member
   - Return book and verify fine calculation
   - Record fine payment

4. **Customize Settings**
   - Edit `config/library.php` for fine amounts, issue days
   - Customize views in `src/Views/`

---

**Status:** ✅ Setup System Complete  
**Last Updated:** 2026-05-05

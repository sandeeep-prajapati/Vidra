# LibraryManagement Pro Bundle

Complete school library management system with book catalog, member registration, issue/return workflows, fine calculation, and reporting.

**Version:** 1.0.0  
**Author:** Vidra  
**Status:** Production Ready

---

## 📦 Installation

### Method 1: Via Bundle Installer UI
1. Go to `/bundle-installer`
2. Upload the LibraryManagement.zip file
3. System auto-registers everything
4. Navigate to `/library/setup`

### Method 2: Manual Installation
1. Extract bundle to `app/Packages/Pro/LibraryManagement/`
2. Register provider in `bootstrap/providers.php` (if not auto-registered)
3. Run setup wizard at `/library/setup`

---

## 🚀 Quick Start

### 1. Run Setup Wizard
Visit: **`http://localhost:8000/library/setup`**

The setup wizard provides:
- ✅ Database migrations
- ✅ Permission creation
- ✅ Role assignment
- ✅ Initial data seeding

**Choose Option:** "Run All Setup" (runs all steps automatically)

### 2. Access Main Module
After setup completes:
- Books: `http://localhost:8000/library/books`
- Members: `http://localhost:8000/library/members`
- Issues: `http://localhost:8000/library/issues`
- Fines: `http://localhost:8000/library/fines`

---

## 🔧 Setup Route

### Endpoint: `/library/setup`
Shows setup status and provides buttons to run installation commands.

**Features:**
- Real-time setup status (Migrations, Permissions, Roles, Data)
- One-click "Run All Setup" button
- Individual step execution
- Progress indication
- Status messages

**What it does:**
```
Setup Route → SetupController
├── Runs Migrations (6 tables)
├── Creates Permissions (5 permissions)
├── Sets up Roles (Admin, Librarian, Teacher)
└── Seeds Initial Data (6 book categories)
```

### Custom Command Execution

You can extend SetupController to run custom commands:

```php
// Add to SetupController.php
private function runCustomCommand(): array
{
    try {
        Artisan::call('your-custom-command');
        return [
            'success' => true,
            'message' => 'Custom command executed',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}

// Add to runSetup() method
case 'custom' => $this->runCustomCommand(),
```

---

## 📚 Features

### Book Management
- ✅ Add/edit/delete books with multi-copy support
- ✅ Book categories (Fiction, Science, Reference, etc.)
- ✅ Track available vs issued copies
- ✅ Search by title, author, ISBN
- ✅ Book status (active/inactive)

### Member Management
- ✅ Register students and staff as members
- ✅ Configurable borrowing limits (max books)
- ✅ Membership validity tracking
- ✅ Status management (active/suspended/expired)
- ✅ Member activity history

### Issue & Return Workflow
- ✅ Issue books with configurable due date (default: 14 days)
- ✅ Return books with automatic processing
- ✅ Overdue detection and marking
- ✅ Issue history per member
- ✅ Support for multiple active issues per member

### Fine Management
- ✅ Automatic fine calculation for overdue books
- ✅ Configurable fine per day (₹10.00 default)
- ✅ Record partial payments
- ✅ Waive fines with audit trail
- ✅ Collection reports and summaries

### Book Reservations
- ✅ Queue system when all copies are issued
- ✅ Notification when book becomes available
- ✅ Reservation expiry tracking
- ✅ Automatic fulfillment workflow

---

## 🔐 Permissions

### Available Permissions
```
view_library-management           → View all library pages
create_library-management_item    → Add books, members, issues
edit_library-management_item      → Edit books, return books
delete_library-management_item    → Delete books
manage_library_fines              → Record payments, waive fines
```

### Role Assignments

**Admin Role**
- ✅ All permissions (full access)

**Librarian Role** (Created by setup)
- ✅ All permissions (full access)

**Teacher Role**
- ✅ View books only (can't modify)

### Custom Role Assignment
```php
// In tinker
use Spatie\Permission\Models\Role, Permission;

$librarian = Role::where('name', 'librarian')->first();
$librarian->givePermissionTo('manage_library_fines');
```

---

## 📍 Routes

All routes prefixed with `/library` and protected with auth middleware:

```
GET     /library/books                    → List books
GET     /library/books/create             → Add book form
POST    /library/books                    → Store book
GET     /library/books/{id}/edit          → Edit book form
PUT     /library/books/{id}               → Update book
DELETE  /library/books/{id}               → Delete book
GET     /library/books/search             → Search books

GET     /library/members                  → List members
GET     /library/members/create           → Register member form
POST    /library/members                  → Store member
GET     /library/members/{id}/edit        → Edit member
GET     /library/members/{id}             → View member profile
PUT     /library/members/{id}             → Update member

GET     /library/issues                   → List active issues
GET     /library/issues/create            → Issue book form
POST    /library/issues                   → Issue book
POST    /library/issues/{id}/return       → Return book
GET     /library/issues/overdue           → List overdue books

GET     /library/fines                    → List all fines
GET     /library/fines/pending            → List pending fines
POST    /library/fines/{id}/payment       → Record payment
POST    /library/fines/{id}/waive         → Waive fine
GET     /library/fines/report             → Fine collection report
```

---

## ⚙️ Configuration

Edit `src/Config/library.php` to customize:

```php
'fine_per_day' => 10.0,              // Amount in rupees per day
'max_issue_days' => 14,              // Default days to borrow
'max_books_per_member' => 3,         // Books allowed per member
'max_fine_per_book' => 500.0,        // Maximum fine cap
'reservation_expiry_days' => 7,      // Reservation validity
'membership_validity_years' => 1,    // Member card validity
```

---

## 🗄️ Database Schema

### Tables Created
```
library_categories        → Book categories
library_books            → Book catalog
library_members          → Member registry
library_issues           → Issue/return transactions
library_fines            → Fine records
library_reservations     → Book reservations queue
```

### Key Relationships
```
Category  ─────────── hasMany ─────────── Books
             ↓
Member ─────── hasMany ─────── Issues
    ↓                                ↓
  hasMany Fines ←────────────────── hasSub Fine
    ↓
hasMany Reservations
```

---

## 🧪 Testing

### Check Installation
```bash
# List library routes
php artisan route:list | grep library

# Check permissions
php artisan tinker
>>> use Spatie\Permission\Models\Permission;
>>> Permission::where('name', 'like', '%library%')->count()
=> 5

# Check roles
>>> use Spatie\Permission\Models\Role;
>>> Role::pluck('name')
=> ["admin", "teacher", "librarian"]
```

### Test Setup Endpoint
```bash
# Visit setup page
curl http://localhost:8000/library/setup

# Run migrations via API
curl -X POST http://localhost:8000/library/setup/run \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {{csrf_token}}" \
  -d '{"command":"migrate"}'
```

---

## 📝 Usage Examples

### Issue a Book
```php
use App\Packages\Pro\LibraryManagement\Services\IssueService;
use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;

$service = app(IssueService::class);
$book = LibraryBook::find(1);
$member = LibraryMember::find(1);

$issue = $service->issueBook($book, $member, auth()->id(), 14);
```

### Record Fine Payment
```php
use App\Packages\Pro\LibraryManagement\Services\FineService;
use App\Packages\Pro\LibraryManagement\Models\LibraryFine;

$service = app(FineService::class);
$fine = LibraryFine::find(1);

$service->recordPayment($fine, 50.0); // Pay ₹50
```

### Get Member Profile
```php
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;

$member = LibraryMember::with('issues', 'fines')->find(1);

$member->getActiveIssuesCount();      // Count active issues
$member->getTotalFinesAmount();       // Total pending fines
$member->canBorrowMore();             // Can borrow next book?
```

---

## 🛠️ Customization

### Add Custom Setup Command
Edit `src/Controllers/SetupController.php`:
```php
case 'custom-command' => $this->customCommand(),

private function customCommand(): array
{
    // Your logic here
    return ['success' => true, 'message' => '...'];
}
```

### Extend Models
```php
// In your app code
use App\Packages\Pro\LibraryManagement\Models\LibraryBook;

LibraryBook::addGlobalScope(new YourScope());
```

### Override Views
Create views at same path in your app:
```
resources/views/library-management/books/index.blade.php
```

---

## 📦 Bundle Files

```
LibraryManagement/
├── manifest.json                    ← Bundle metadata
├── README.md                        ← This file
├── IMPLEMENTATION_SUMMARY.md        ← Technical details
└── src/
    ├── Config/
    │   └── library.php
    ├── Controllers/
    │   ├── BookController.php
    │   ├── MemberController.php
    │   ├── IssueController.php
    │   ├── FineController.php
    │   └── SetupController.php       ← Setup wizard
    ├── Models/
    │   ├── LibraryCategory.php
    │   ├── LibraryBook.php
    │   ├── LibraryMember.php
    │   ├── LibraryIssue.php
    │   ├── LibraryFine.php
    │   └── LibraryReservation.php
    ├── Services/
    │   ├── BookService.php
    │   ├── IssueService.php
    │   └── FineService.php
    ├── Repositories/
    │   ├── BookRepository.php
    │   ├── IssueRepository.php
    │   └── FineRepository.php
    ├── Database/
    │   └── migrations/ (6 migrations)
    ├── Providers/
    │   ├── LibraryManagementServiceProvider.php
    │   └── MenuProvider.php
    ├── Routes/
    │   └── web.php
    └── Views/
        ├── setup/
        │   └── index.blade.php        ← Setup page
        ├── books/
        ├── members/
        ├── issues/
        └── fines/
```

---

## 🆘 Troubleshooting

**Setup route not found?**
- Check `bootstrap/providers.php` includes `LibraryManagementServiceProvider`
- Run `php artisan cache:clear && composer dump-autoload`

**Permissions not working?**
- Ensure `spatie/laravel-permission` is installed
- Run setup wizard to create permissions

**Database tables don't exist?**
- Visit `/library/setup` and click "Run Migrations"
- Or run: `php artisan migrate`

**Can't access `/library/books`?**
- Check you're logged in (auth required)
- Check user has `view_library-management` permission
- Try assigning `librarian` role to test user

---

## 📞 Support

- **Setup Issues:** Visit `/library/setup` for diagnostic page
- **Bundle Docs:** See `IMPLEMENTATION_SUMMARY.md`
- **Core Docs:** See `/app/Packages/BundleInstaller/`

---

**Last Updated:** 2026-05-05  
**Status:** ✅ Production Ready

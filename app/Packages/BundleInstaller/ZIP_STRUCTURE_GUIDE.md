# Bundle ZIP Structure Guide

## ⚠️ Critical Issue: "manifest.json not found in bundle"

This error occurs when the ZIP file structure is incorrect.

## The Problem

```
WRONG ❌ - This will cause "manifest.json not found" error:

MyBundle.zip
└── MyBundle/
    ├── manifest.json
    ├── README.md
    └── src/
```

When you create a ZIP like this:
```bash
zip -r MyBundle.zip MyBundle/
```

The ZIP contains a nested folder, and the validator looks for `manifest.json` at the root.

## The Solution

```
CORRECT ✅ - This will work perfectly:

MyBundle.zip
├── manifest.json
├── README.md
└── src/
```

Create the ZIP from INSIDE the bundle directory:

```bash
cd app/Packages/Pro/MyBundle
zip -r ../MyBundle.zip .
cd ..
# Now you have MyBundle.zip with correct structure
```

## Step-by-Step Instructions

### 1. Navigate into your bundle directory
```bash
cd app/Packages/Pro/MyBundle
```

### 2. Create ZIP from current directory (.)
```bash
zip -r ../MyBundle.zip .
```

The key is the `.` at the end (current directory), not the folder name!

### 3. Verify the structure
```bash
unzip -l ../MyBundle.zip | head -20
```

**Output should look like:**
```
Archive:  ../MyBundle.zip
  Length      Date    Time    Name
---------  ---------- -----   ----
      329  2026-05-05 20:54   manifest.json        ← At root!
        0  2026-05-05 20:54   README.md
        0  2026-05-05 20:54   src/
        0  2026-05-05 20:54   src/Providers/
```

**NOT like:**
```
Archive:  ../MyBundle.zip
  Length      Date    Time    Name
---------  ---------- -----   ----
        0  2026-05-05 20:54   MyBundle/           ← ❌ Nested!
      329  2026-05-05 20:54   MyBundle/manifest.json
```

### 4. Upload via Bundle Installer UI
Go to `/bundle-installer` and upload `MyBundle.zip`

## Complete Example

```bash
# 1. Create bundle using artisan
php artisan bundle:create StudentPremium --pro

# 2. Edit your files
# Edit src/Providers/StudentPremiumServiceProvider.php
# Edit src/Routes/web.php
# Create src/Controllers/StudentPremiumController.php
# Create src/Views/index.blade.php
# etc.

# 3. Create ZIP with correct structure
cd app/Packages/Pro/StudentPremium
zip -r ../StudentPremium.zip .

# 4. Verify
unzip -l ../StudentPremium.zip | head -10

# 5. Upload
# Go to http://localhost:8000/bundle-installer
# Upload StudentPremium.zip
# ✅ Success!
```

## Common Mistakes

### ❌ Mistake 1: Using folder name in zip
```bash
cd app/Packages/Pro
zip -r StudentPremium.zip StudentPremium/  # WRONG!
# Creates: StudentPremium.zip -> StudentPremium/ -> manifest.json
```

### ✅ Correct: Using . (current dir)
```bash
cd app/Packages/Pro/StudentPremium
zip -r ../StudentPremium.zip .  # CORRECT!
# Creates: StudentPremium.zip -> manifest.json
```

### ❌ Mistake 2: Zipping parent directory
```bash
cd app/Packages
zip -r Pro/StudentPremium.zip Pro/StudentPremium/  # WRONG!
```

### ✅ Correct: From bundle directory
```bash
cd app/Packages/Pro/StudentPremium
zip -r ../StudentPremium.zip .  # CORRECT!
```

## Excluding Files

You may want to exclude certain files from the ZIP:

```bash
cd app/Packages/Pro/StudentPremium
zip -r ../StudentPremium.zip . -x "*.git*" "node_modules/*" "vendor/*" "*.env*"
```

Files to exclude:
- `vendor/` - Not needed (user installs dependencies)
- `node_modules/` - Not needed
- `.git/` - Not needed
- `.env` - Has sensitive data
- `composer.lock` - Will be regenerated
- `.phpunit.result.cache` - Cache file

## Troubleshooting

### Error: "manifest.json not found in bundle"

**Solution**: Verify ZIP structure with `unzip -l`:
```bash
unzip -l MyBundle.zip | head -20
```

- If you see `MyBundle/manifest.json` → ZIP is wrong, recreate it
- If you see just `manifest.json` → ZIP is correct, check file content

### Error: "Invalid manifest.json format"

**Cause**: The manifest.json file has syntax errors

**Solution**: Validate JSON:
```bash
cat app/Packages/Pro/MyBundle/manifest.json | python -m json.tool
```

Should output without errors. Check for:
- Missing commas
- Unescaped backslashes (use `\\`)
- Missing quotes
- Trailing commas

### Error: "Package already exists"

**Cause**: Bundle with same package_path is already installed

**Solution**: Either:
1. Use a different `package_path` in manifest.json
2. Remove the existing bundle from `/bundle-installer`
3. Rename your bundle folder

## Bundle Storage Location

After upload, bundles are stored at:
```
app/Packages/{package_path}/
```

Example:
```
app/Packages/Pro/StudentPremium/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    ├── Controllers/
    ├── Routes/
    ├── Views/
    ├── Models/
    └── Database/
```

## Testing Your Bundle

Before uploading:

```bash
# 1. Check routes work
php artisan route:list | grep your-bundle

# 2. Check namespace works
php artisan tinker
>>> class_exists('App\Packages\Pro\StudentPremium\Controllers\StudentPremiumController')
=> true

# 3. Check views
# Should see files in src/Views/

# 4. Check migrations (if any)
php artisan migrate --dry-run

# 5. Test in browser
# Visit your bundle route after upload
```

## Quick Reference

| Task | Command |
|------|---------|
| Create bundle | `php artisan bundle:create MyBundle --pro` |
| Go into bundle | `cd app/Packages/Pro/MyBundle` |
| Create ZIP correctly | `zip -r ../MyBundle.zip .` |
| Verify ZIP structure | `unzip -l ../MyBundle.zip \| head` |
| Check JSON validity | `python -m json.tool manifest.json` |
| Test namespace | `php artisan tinker` + `class_exists(...)` |
| Upload bundle | Go to `/bundle-installer` → Upload ZIP |

## Related Files

- `README.md` - Complete bundle system documentation
- `QUICK_START.md` - Quick reference guide (60 seconds)
- `BUNDLE_NAMING_CONVENTION.md` - Naming rules and conventions
- `EXAMPLES.md` - Real-world examples with code
- `../Pro/DemoBundle/README.md` - Sample bundle documentation

## Support

If you still get "manifest.json not found" error:

1. Run `unzip -l MyBundle.zip | head -5` to verify structure
2. Check that first file listed is `manifest.json` (not `MyBundle/manifest.json`)
3. Recreate ZIP using `cd MyBundle && zip -r ../MyBundle.zip .`
4. Check application logs at `/storage/logs/laravel.log` for detailed error

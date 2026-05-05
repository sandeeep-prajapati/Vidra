# Demo Bundle

A sample premium bundle demonstrating the bundle installation system for Vidra.

## Installation

This bundle is included as a reference. To use it:
- Navigate to `/demo` to see the sample views
- Navigate to `/demo/features` to see feature documentation

## Creating Your Own Bundle

1. Copy this bundle folder and rename it to your bundle name
2. Update the `manifest.json` file
3. Modify the service provider, controllers, views, and routes
4. Create a ZIP file: `zip -r MyBundle.zip MyBundle/` (from inside the parent directory)
5. Upload via the Bundle Installer UI at `/bundle-installer`

## Important: ZIP Structure

When creating a ZIP file, the manifest.json must be at the ROOT of the ZIP:

**CORRECT ✅:**
```
MyBundle.zip
├── manifest.json
├── src/
│   └── ...
└── README.md
```

**INCORRECT ❌:**
```
MyBundle.zip
└── MyBundle/
    ├── manifest.json
    └── src/
```

To create the correct structure:
```bash
cd app/Packages/Pro
cd MyBundle  # Go INTO the bundle directory
zip -r ../MyBundle.zip .  # Create ZIP from current directory
cd ..
# Now you have MyBundle.zip ready to upload
```

For more information, see:
- `BUNDLE_NAMING_CONVENTION.md` - Complete naming convention
- `QUICK_START.md` - Quick reference guide
- `EXAMPLES.md` - Real examples

#!/bin/bash

# Sample Bundle Creation Script
# This script creates a sample bundle structure that can be uploaded to the Bundle Installer

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BUNDLE_NAME=${1:-"SampleModule"}
BUNDLE_VERSION=${2:-"1.0.0"}
BUNDLE_AUTHOR=${3:-"Developer"}
BUNDLE_DESC=${4:-"A sample module bundle"}

# Derived values
SNAKE_NAME=$(echo "$BUNDLE_NAME" | sed -r 's/([A-Z])/-\L\1/g' | sed 's/^-//')
PACKAGE_NAME=$(echo "$BUNDLE_NAME" | sed 's/-//g')
OUTPUT_DIR="${BUNDLE_NAME}-v${BUNDLE_VERSION}"

echo -e "${YELLOW}Creating Bundle: ${BUNDLE_NAME}${NC}"
echo "Version: $BUNDLE_VERSION"
echo "Author: $BUNDLE_AUTHOR"
echo ""

# Create directory structure
mkdir -p "$OUTPUT_DIR/src/{Providers,Controllers,Models,"Database/migrations",Routes,"Resources/views"}"

echo -e "${GREEN}✓${NC} Created directory structure"

# Create manifest.json
cat > "$OUTPUT_DIR/manifest.json" << EOF
{
  "name": "$BUNDLE_NAME",
  "version": "$BUNDLE_VERSION",
  "description": "$BUNDLE_DESC",
  "author": "$BUNDLE_AUTHOR",
  "provider_class": "App\\\\Packages\\\\$PACKAGE_NAME\\\\Providers\\\\${PACKAGE_NAME}ServiceProvider",
  "package_path": "$PACKAGE_NAME"
}
EOF

echo -e "${GREEN}✓${NC} Created manifest.json"

# Create ServiceProvider
cat > "$OUTPUT_DIR/src/Providers/${PACKAGE_NAME}ServiceProvider.php" << 'EOF'
<?php

namespace App\Packages\$PACKAGE_NAME\Providers;

use Illuminate\Support\ServiceProvider;

class $PACKAGE_NAMEServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', '$SNAKE_NAME');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}
EOF

# Replace placeholders
sed -i "s/\$PACKAGE_NAME/$PACKAGE_NAME/g" "$OUTPUT_DIR/src/Providers/${PACKAGE_NAME}ServiceProvider.php"
sed -i "s/\$SNAKE_NAME/$SNAKE_NAME/g" "$OUTPUT_DIR/src/Providers/${PACKAGE_NAME}ServiceProvider.php"

echo -e "${GREEN}✓${NC} Created ServiceProvider"

# Create sample Controller
cat > "$OUTPUT_DIR/src/Controllers/DemoController.php" << 'EOF'
<?php

namespace App\Packages\$PACKAGE_NAME\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function index(): View
    {
        return view('$SNAKE_NAME::index', [
            'message' => '$PACKAGE_NAME is installed and working!',
        ]);
    }
}
EOF

sed -i "s/\$PACKAGE_NAME/$PACKAGE_NAME/g" "$OUTPUT_DIR/src/Controllers/DemoController.php"
sed -i "s/\$SNAKE_NAME/$SNAKE_NAME/g" "$OUTPUT_DIR/src/Controllers/DemoController.php"

echo -e "${GREEN}✓${NC} Created sample Controller"

# Create sample Model
cat > "$OUTPUT_DIR/src/Models/Demo.php" << 'EOF'
<?php

namespace App\Packages\$PACKAGE_NAME\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    protected $table = '$SNAKE_NAME_demos';
    protected $fillable = ['name', 'description'];
    public $timestamps = true;
}
EOF

sed -i "s/\$PACKAGE_NAME/$PACKAGE_NAME/g" "$OUTPUT_DIR/src/Models/Demo.php"
sed -i "s/\$SNAKE_NAME/$SNAKE_NAME/g" "$OUTPUT_DIR/src/Models/Demo.php"

echo -e "${GREEN}✓${NC} Created sample Model"

# Create migration
TIMESTAMP=$(date +%Y%m%d%H%M%S)
cat > "$OUTPUT_DIR/src/Database/migrations/${TIMESTAMP}_create_${SNAKE_NAME}_demos_table.php" << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('$SNAKE_NAME_demos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$SNAKE_NAME_demos');
    }
};
EOF

sed -i "s/\$SNAKE_NAME/$SNAKE_NAME/g" "$OUTPUT_DIR/src/Database/migrations/${TIMESTAMP}_create_${SNAKE_NAME}_demos_table.php"

echo -e "${GREEN}✓${NC} Created migration"

# Create routes
cat > "$OUTPUT_DIR/src/Routes/web.php" << 'EOF'
<?php

use App\Packages\$PACKAGE_NAME\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/$SNAKE_NAME', [DemoController::class, 'index'])->name('$SNAKE_NAME.index');
});
EOF

sed -i "s/\$PACKAGE_NAME/$PACKAGE_NAME/g" "$OUTPUT_DIR/src/Routes/web.php"
sed -i "s/\$SNAKE_NAME/$SNAKE_NAME/g" "$OUTPUT_DIR/src/Routes/web.php"

echo -e "${GREEN}✓${NC} Created routes"

# Create sample view
cat > "$OUTPUT_DIR/src/Resources/views/index.blade.php" << 'EOF'
@extends('core-package::layouts.app')

@section('breadcrumb')
    <nav style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;">
        <a href="{{ route('home') }}" style="color:#0ea5e9;">Home</a>
        <span style="color:#cbd5e1;">/</span>
        <span style="color:#475569;">$PACKAGE_NAME</span>
    </nav>
@endsection

@section('content')
    <div style="padding:1.5rem;max-width:64rem;margin:0 auto;">
        <x-core-package::card title="$PACKAGE_NAME" subtitle="Welcome to your new bundle!">
            <p style="color:#475569;margin:0;">
                {{ $message }}
            </p>
        </x-core-package::card>
    </div>
@endsection
EOF

sed -i "s/\$PACKAGE_NAME/$PACKAGE_NAME/g" "$OUTPUT_DIR/src/Resources/views/index.blade.php"

echo -e "${GREEN}✓${NC} Created sample view"

# Create README
cat > "$OUTPUT_DIR/README.md" << EOF
# $BUNDLE_NAME Bundle v$BUNDLE_VERSION

$BUNDLE_DESC

## Installation

Upload this bundle via the Bundle Installer at \`/bundle-installer\` in your application.

## Author

$BUNDLE_AUTHOR

## Version

$BUNDLE_VERSION
EOF

echo -e "${GREEN}✓${NC} Created README.md"

# Create ZIP
zip -r -q "${OUTPUT_DIR}.zip" "$OUTPUT_DIR"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Created ZIP file: ${OUTPUT_DIR}.zip"
    rm -rf "$OUTPUT_DIR"
    echo ""
    echo -e "${GREEN}Success!${NC} Your bundle is ready for upload."
    echo "Upload ${OUTPUT_DIR}.zip via /bundle-installer"
else
    echo -e "${RED}✗${NC} Failed to create ZIP file"
    exit 1
fi
EOF

chmod +x /home/sandeep/Desktop/school-management-app/laravel/app/Packages/BundleInstaller/resources/create-sample-bundle.sh

echo -e "${GREEN}✓${NC} Script created at: laravel/app/Packages/BundleInstaller/resources/create-sample-bundle.sh"

## Installation

Vidra offers multiple installation methods depending on your deployment needs.

---

## Option A — Web Installer (Recommended)

The easiest way to install Vidra is through the built-in web installer.

### 1. Clone the Repository

```bash
git clone https://github.com/openvidra/Vidra.git school-management-app
cd school-management-app
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install & Build Frontend Assets

```bash
npm install
npm run build
```

### 4. Configure Environment File

```bash
cp .env.example .env
```

### 5. Set Required Folder Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### 6. Start Local Development Server

*(Skip this if you are using Apache or Nginx)*

```bash
php artisan serve
```

### 7. Launch the Installer

Open your browser and visit:

```text
http://localhost:8000/install
```

The installation wizard will guide you through:

✅ Server requirements check
✅ Database configuration *(automatically updates `.env` and runs migrations)*
✅ Default data seeding
✅ Administrator account creation

Once installation is completed, Vidra will automatically redirect you to:

```text
/login
```

---

## Option B — Artisan CLI Installer

For headless servers, VPS deployments, or automated setups, Vidra includes a built-in Artisan installer.

### Full Interactive Installation

```bash
php artisan school:install
```

### Skip Environment Configuration

*(Use this if `.env` is already configured)*

```bash
php artisan school:install --skip-env-check
```

### Skip Administrator Creation

```bash
php artisan school:install --skip-admin-creation
```

### During Installation, You’ll Be Asked For

* Database driver *(MySQL / PostgreSQL)*
* Database host & port
* Database name
* Username & password
* Application URL
* Administrator name, email, and password

---

## Option C — Manual Installation

If you prefer complete control over the installation process, follow these steps.

### 1. Clone Repository & Install Dependencies

```bash
git clone https://github.com/openvidra/Vidra.git school-management-app
cd school-management-app

composer install
npm install && npm run build
```

### 2. Configure Environment

```bash
cp .env.example .env
```

Edit the `.env` file and configure the following:

```env
DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
APP_URL=
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Seed Default Data

```bash
php artisan db:seed
```

### 6. Create Storage Symlink

```bash
php artisan storage:link
```

### 7. Clear & Optimize Cache

```bash
php artisan optimize:clear
```

### 8. Create First Administrator Account

```bash
php artisan school:install --skip-env-check
```

---

## Post-Installation Commands

| Action                                | Command                      |
| ------------------------------------- | ---------------------------- |
| Start development server              | `php artisan serve`          |
| Compile frontend assets (development) | `npm run dev`                |
| Compile frontend assets (production)  | `npm run build`              |
| Clear all caches                      | `php artisan optimize:clear` |
| Run tests                             | `php artisan test`           |

---

## Default Login

After installation, open:

```text
http://localhost:8000/login
```

You can log in using the administrator account created during setup.

# Sotelo Management - Project Setup Guide

## Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- A web browser

## Step 1: Install PHP & MySQL (if not already installed)

### macOS (using Homebrew)
```bash
brew install php mysql
brew services start mysql
```

### Windows (using XAMPP)
Download and install XAMPP from https://www.apachefriends.org/
XAMPP includes PHP, MySQL, and Apache all-in-one.

### Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install php php-pdo php-mysql mysql-server
sudo systemctl start mysql
```

## Step 2: Create the Database

Open terminal and run:

```bash
mysql -u root -p
```

Then in the MySQL prompt:

```sql
CREATE DATABASE sotelo_db;
EXIT;
```

> **Note:** If your MySQL root user has no password, just press Enter when prompted.

## Step 3: Configure Database Connection

Open `config/database.php` and update the credentials if needed:

```php
return [
    'DB_HOST' => 'localhost',
    'DB_PORT' => 3306,
    'DB_NAME' => 'sotelo_db',
    'DB_USER' => 'root',      // your MySQL username
    'DB_PASS' => '',           // your MySQL password
];
```

## Step 4: Start the Server

Open terminal, navigate to the project folder, and run:

```bash
cd /path/to/rental-project-structure
php -S localhost:8000 server.php
```

You should see:
```
PHP Development Server (http://localhost:8000) started
```

## Step 5: Initialize the Database

Open your browser and go to:

```
http://localhost:8000/init-db
```

This will create all tables and seed sample data automatically.
You'll see a success page with credentials, then it auto-redirects to the homepage.

## Step 6: Access the Application

| Page | URL |
|------|-----|
| Homepage | http://localhost:8000 |
| Rental Application | http://localhost:8000/application |

Click the **"Client Portal"** button on the homepage to log in.

### Login Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | password |
| Renter | test | password |

- **Admin** login goes to the Admin Dashboard (properties, payments, maintenance, applications)
- **Renter** login goes to the Renter Portal (dashboard, payments, maintenance, documents, messages)

## Troubleshooting

### "Connection refused" or database error
- Make sure MySQL is running: `brew services start mysql` (macOS) or `sudo systemctl start mysql` (Linux)
- Check `config/database.php` has the correct username/password

### Port 8000 already in use
- Use a different port: `php -S localhost:8080 server.php`

### Blank page or 500 error
- Check PHP errors: `php -S localhost:8000 server.php 2>&1`
- Make sure PHP PDO MySQL extension is enabled: `php -m | grep pdo_mysql`

### XAMPP Users (Windows)
Instead of `php -S`, you can:
1. Copy this project folder to `C:\xampp\htdocs\sotelo`
2. Start Apache and MySQL from XAMPP Control Panel
3. Access via `http://localhost/sotelo/public/`

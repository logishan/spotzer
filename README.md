# Portal - User Management System

A Laravel-based user management system with role-based access control and department management.

## Features

- User Management
  - Create, edit, and delete users
  - Assign multiple roles to users
  - Assign users to departments
  - Active/Inactive status management

- Role Management
  - Create, edit, and delete roles
  - Role hierarchy with levels
  - Track users per role

- Department Management
  - Create, edit, and delete departments
  - Support for parent/child department structure
  - Track users per department

## Requirements

- PHP 8.1 or higher
- Laravel 10.x
- MySQL/PostgreSQL
- Composer
- Node.js & NPM

## Installation

1. Clone the repository
```bash
git clone [your-repository-url]
cd portal
```

2. Install PHP dependencies
```bash
composer install
```

3. Install and compile frontend dependencies
```bash
npm install
npm run dev
```

4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env` file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations
```bash
php artisan migrate
```

## Usage

1. Create an admin user:
```bash
php artisan make:admin
```

2. Access the application at `http://localhost`

## Security

If you discover any security-related issues, please email [your-email] instead of using the issue tracker.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details


# School Management

## Getting Started

Clone the repository:
```
git clone https://github.com/username/project-name.git
```

Install composer packages:
```
composer install
```

Copy the .env.example file and rename it to .env:
```
cp .env.example .env
```

Generate a new application key:
```
php artisan key:generate
```

Update the .env file with your database credentials:
```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Run database migrations and seeders:
```
php artisan migrate --seed
```

Install npm packages and compile the assets and start the frontend development server:
```
npm install
npm run dev
```

On separate window start development server:
```
php artisan serve
```

### Troubleshooting
```
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Contributing
Please make sure to update tests as appropriate. No features or bug fixes can be merged without accompanying tests to ensure that the changes don't introduce new issues.

If you're contributing to the backend, please write tests for any new functionality or changes to existing functionality. Tests should cover edge cases as well as typical use cases.

If you're contributing to the frontend, please make sure that your changes don't break existing functionality and test on multiple browsers and devices.

Please also make sure that your code follows the project's coding standards and conventions.


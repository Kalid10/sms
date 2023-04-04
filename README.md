# School Management

## Getting Started

Fork and clone the repository:

```
git clone https://github.com/user-name/rigel.git
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

#### Queue Configuration

To enable efficient background processing of the import tasks, make sure to configure your queue database connection in
the .env file:

```QUEUE_CONNECTION=database```

If you are using a different queue connection like Redis, update the ```QUEUE_CONNECTION``` value accordingly.

#### Running the Queue Worker

To process the jobs in the background, you need to run the Laravel queue worker. In your terminal, navigate to the
project root and execute the following command:

```php artisan queue:work```

This command will start the queue worker, listening for new jobs and processing them in the background. For production
environments, consider using a process manager like Supervisor to manage the queue workers.

Install npm packages and compile the assets and start the frontend development server:

```
npm install
npm run dev
```

On separate window start development server:

```
php artisan serve
```

Make sure to set the ```DEFAULT_MIN_STUDENTS``` and ```DEFAULT_MAX_STUDENTS``` variables in the .env file, which define
the default minimum and maximum number of students allowed in a batch. These values are used to configure the
application and ensure proper batch management.

### Troubleshooting

```

php artisan cache:clear
php artisan config:clear
php artisan view:clear

```

### Contributing

Please make sure to update tests as appropriate. No features or bug fixes can be merged without accompanying tests to
ensure that the changes don't introduce new issues.

If you're contributing to the backend, please write tests for any new functionality or changes to existing
functionality. Tests should cover edge cases as well as typical use cases.

If you're contributing to the frontend, please make sure that your changes don't break existing functionality and test
on multiple browsers and devices.

Please also make sure that your code follows the project's coding standards and conventions.


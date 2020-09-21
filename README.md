## Smart Pension tech task
The tool is meant to monitor access log data. Displays how many times specific routes were visited, as well as how many unique users visited the routes.

This tool provides two ways to aggregate webserver.log file
1. In memory aggregation
2. Aggregation with database usage (optional)

### Installation

```bash
composer install
```
### In memory agregation

##### Tests

```bash
php artisan test --filter AggregateInMemoryTest
```

##### usage

```bash
php artisan parse webserver.log
```

### Database aggregation (optional)


To use database aggregation:
 1. create db
 2. configure db connection by filling variables in .env file
 3. run migration command to create a table
 4. run tests
 
Environment variables 
```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
```

Run migration command to create table
```bash
php artisan migrate
```

Run tests

```bash
php artisan test --filter AggregateInDBTest
```

##### usage

```bash
php artisan parse webserver.log --database
```

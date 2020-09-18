# webserver-log-parser

## Environment configuration

fill these env parameters in .env file

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

## Installation

```bash
composer install
php artisan migrate
php artisan test
```

## Usage

put log file into storage/app directory

to aggregate logs in memory
```bash
php artisan logs:aggregate filename.txt
```

to aggregate logs in memory in db 
```bash
php artisan logs:aggregate filename.txt --database
```

## though the best way is to write logs straight in to DB table

